<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\AndroidNotification;
use Kreait\Firebase\Messaging\ApnsConfig;

class NotificationController extends Controller
{
    public function sendNotification()
    {
        $users = User::where(function ($query) {
            $query->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                ->orWhereNotNull('firebase_token')->where('firebase_token', '!=', '');
        })
        ->orderBy('fname', 'asc')
        ->get(['id', 'fname', 'lname', 'email', 'firebase_token', 'fcm_token']);

        return view('admin.notification.send_notification', compact('users'));
    }

    public function alldNotification()
    {
        $notifications = DB::table('notification_for_apps')
            ->leftJoin('users', 'notification_for_apps.user_id', '=', 'users.id')
            ->select(
                'notification_for_apps.*',
                'users.fname',
                'users.lname',
                'users.email',
                'users.user_name'
            )
            ->orderBy('notification_for_apps.created_at', 'desc')
            ->get();

        return view('admin.notification.all_notification', compact('notifications'));
    }

    public function storeNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required',
        ]);

        $title = $request->title;
        $descriptionHtml = $request->description ?? ''; // حفظ الوصف كـ HTML كما هو
        $descriptionPlain = strip_tags($descriptionHtml); // نسخة نصية للاستخدام الداخلي فقط
        $userId = $request->user_id;
        // رقم الـ badge يُحسب تلقائياً من عدد الإشعارات غير المقروءة لكل مستخدم

        $sentCount = 0;
        $firebaseError = false;
        $errorMessage = '';

        // 1. التخزين المحلي للإشعار كخطوة أولى دائمة
        try {
            if ($userId === 'all') {
                $users = User::where(function($q) {
                    $q->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                      ->orWhereNotNull('firebase_token')->where('firebase_token', '!=', '');
                })->get();

                if ($users->isNotEmpty()) {
                    Notification::send($users, new AdminPushNotification($title, $descriptionPlain));
                }
            } else {
                $user = User::find($userId);
                if ($user) {
                    $user->notify(new AdminPushNotification($title, $descriptionPlain));
                }
            }
        } catch (\Exception $dbEx) {
            \Log::error('Database Notification Save Error: ' . $dbEx->getMessage());
        }

        // 2. محاولة الإرسال الفعلي عبر Firebase (العنوان فقط، بدون body)
        try {
            $messaging = Firebase::messaging();

            if ($userId === 'all') {
                $tokens = User::where(function($q) {
                    $q->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                      ->orWhereNotNull('firebase_token')->where('firebase_token', '!=', '');
                })
                ->get()
                ->map(function ($u) {
                    return $u->fcm_token ?: $u->firebase_token;
                })
                ->filter()
                ->unique()
                ->toArray();

                if (!empty($tokens)) {
                    // إرسال فردي لكل مستخدم مع حساب badge خاص به
                    $allUsers = User::where(function($q) {
                        $q->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                          ->orWhereNotNull('firebase_token')->where('firebase_token', '!=', '');
                    })->get();

                    foreach ($allUsers as $singleUser) {
                        $singleToken = $singleUser->fcm_token ?: $singleUser->firebase_token;
                        if (empty($singleToken)) continue;

                        // حساب عدد الإشعارات غير المقروءة للمستخدم +1 (للإشعار الجديد)
                        $userBadge = DB::table('notification_for_apps')
                            ->where('user_id', $singleUser->id)
                            ->where('user_view', 'no')
                            ->count() + 1;

                        $notification = FirebaseNotification::create($title);

                        $androidConfig = AndroidConfig::fromArray([
                            'notification' => [
                                'sound'      => 'default',
                                'channel_id' => 'high_importance_channel',
                            ],
                            'priority' => 'high',
                        ]);

                        $apnsConfig = ApnsConfig::fromArray([
                            'headers' => [
                                'apns-priority' => '10',
                            ],
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                    'badge' => $userBadge,
                                ],
                            ],
                        ]);

                        $message = CloudMessage::new()
                            ->withToken($singleToken)
                            ->withNotification($notification)
                            ->withAndroidConfig($androidConfig)
                            ->withApnsConfig($apnsConfig)
                            ->withData(['badge' => (string) $userBadge]);

                        try {
                            $messaging->send($message);
                            $sentCount++;
                        } catch (\Exception $sendEx) {
                            \Log::warning('FCM send to user ' . $singleUser->id . ' failed: ' . $sendEx->getMessage());
                        }
                    }
                }
            } else {
                $user = User::find($userId);
                $token = $user ? ($user->fcm_token ?: $user->firebase_token) : null;
                
                if ($user && $token) {
                    // التحقق من صلاحية التوكن بشكل مبدئي لتفادي التوكنات الوهمية مثل "44545"
                    if (strlen($token) < 20) {
                        throw new \Exception('التوكن غير صالح أو تجريبي.');
                    }

                    // حساب عدد الإشعارات غير المقروءة للمستخدم +1 (للإشعار الجديد)
                    $userBadge = DB::table('notification_for_apps')
                        ->where('user_id', $user->id)
                        ->where('user_view', 'no')
                        ->count() + 1;

                    // إرسال العنوان فقط في Push، بدون body، مع صوت
                    $notification = FirebaseNotification::create($title);

                    // إعدادات Android مع صوت
                    $androidConfig = AndroidConfig::fromArray([
                        'notification' => [
                            'sound'      => 'default',
                            'channel_id' => 'high_importance_channel',
                        ],
                        'priority' => 'high',
                    ]);

                    // إعدادات iOS (APNS) مع صوت مع badge محسوب تلقائياً
                    $apnsConfig = ApnsConfig::fromArray([
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => $userBadge,
                            ],
                        ],
                    ]);

                    $message = CloudMessage::new()
                        ->withToken($token)
                        ->withNotification($notification)
                        ->withAndroidConfig($androidConfig)
                        ->withApnsConfig($apnsConfig)
                        ->withData(['badge' => (string) $userBadge]);
                    
                    $messaging->send($message);
                    $sentCount = 1;
                } else {
                    $firebaseError = true;
                    $errorMessage = 'المستخدم لا يملك توكن فعال لإرسال الإشعارات.';
                }
            }
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
            $firebaseError = true;
            $errorMessage = $e->getMessage();
        }

        // 3. التخزين في جدول notification_for_apps (الوصف يُخزن كـ HTML)
        try {
            $now = now();
            $dateString = $now->toDateTimeString();
            if ($userId === 'all') {
                $users = User::where(function($q) {
                    $q->whereNotNull('fcm_token')->where('fcm_token', '!=', '')
                      ->orWhereNotNull('firebase_token')->where('firebase_token', '!=', '');
                })->get();

                $insertData = [];
                foreach ($users as $u) {
                    $insertData[] = [
                        'user_id' => $u->id,
                        'title'   => $title,
                        'des'     => $descriptionHtml, // HTML كما هو
                        'user_view' => 'no',
                        'date'    => $dateString,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (!empty($insertData)) {
                    foreach (array_chunk($insertData, 500) as $chunk) {
                        DB::table('notification_for_apps')->insert($chunk);
                    }
                }
            } else {
                $user = User::find($userId);
                if ($user) {
                    DB::table('notification_for_apps')->insert([
                        'user_id' => $user->id,
                        'title'   => $title,
                        'des'     => $descriptionHtml, // HTML كما هو
                        'user_view' => 'no',
                        'date'    => $dateString,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        } catch (\Exception $appDbEx) {
            \Log::error('notification_for_apps Save Error: ' . $appDbEx->getMessage());
        }

        if ($firebaseError && $sentCount === 0) {
            $notificationAlert = array(
                'message' => 'تم حفظ الإشعار محلياً، ولكن فشل إرساله عبر Firebase: ' . $errorMessage,
                'alert-type' => 'warning'
            );
        } else {
            $notificationAlert = array(
                'message' => 'تم إرسال الإشعار بنجاح عبر Firebase وحفظه محلياً.',
                'alert-type' => 'success'
            );
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => !($firebaseError && $sentCount === 0),
                'message' => $notificationAlert['message'],
                'alert_type' => $notificationAlert['alert-type']
            ]);
        }

        return redirect()->route('all.notification')->with($notificationAlert);
    }

    public function deleteNotification($id)
    {
        $notification = DB::table('notification_for_apps')->where('id', $id)->first();
        
        if ($notification) {
            DB::table('notification_for_apps')->where('id', $id)->delete();

            $notificationAlert = array(
                'message' => 'تم حذف الإشعار بنجاح.',
                'alert-type' => 'success'
            );
        } else {
            $notificationAlert = array(
                'message' => 'الإشعار غير موجود.',
                'alert-type' => 'error'
            );
        }

        return redirect()->back()->with($notificationAlert);
    }

    public function getUnreadNotificationsCountApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $count = DB::table('notification_for_apps')
            ->where('user_id', $request->user_id)
            ->where('user_view', 'no')
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ], 200);
    }

    public function getUserNotificationsApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $notifications = DB::table('notification_for_apps')
            ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ], 200);
    }

    public function updateNotificationStatusApi(Request $request)
    {
        // Support both notification_id/id and status/user_view
        $inputData = $request->all();
        if (isset($inputData['id']) && !isset($inputData['notification_id'])) {
            $inputData['notification_id'] = $inputData['id'];
        }
        if (isset($inputData['user_view']) && !isset($inputData['status'])) {
            $inputData['status'] = $inputData['user_view'];
        }

        $validator = \Validator::make($inputData, [
            'notification_id' => 'required|integer',
            'status' => 'required|string|in:yes,no,delete',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $notificationId = $inputData['notification_id'];
        $status = $inputData['status'];

        $notification = DB::table('notification_for_apps')->where('id', $notificationId)->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود'
            ], 404);
        }

        DB::table('notification_for_apps')
            ->where('id', $notificationId)
            ->update([
                'user_view' => $status,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الإشعار بنجاح',
            'data' => DB::table('notification_for_apps')->where('id', $notificationId)->first()
        ], 200);
    }
}

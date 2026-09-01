<?php

namespace App\Http\Controllers;

use App\Exports\FinancialReportExport;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Notifications\AdminPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Maatwebsite\Excel\Facades\Excel;

class FinancialReportController extends Controller
{
    /**
     * Display all financial transactions and purchases report
     */
    public function allTransactions(Request $request)
    {
        $query = PaymentTransaction::with(['user', 'price', 'gameCoin'])->latest();

        // 1. Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'paid' || $request->status === 'success') {
                $query->paid();
            } elseif ($request->status === 'failed') {
                $query->failed();
            } else {
                $query->where('status', $request->status);
            }
        }

        // 2. Package type filter
        if ($request->filled('package_type') && $request->package_type !== 'all') {
            $query->where('package_type', $request->package_type);
        }

        // 3. Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 4. Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhere('session_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('fname', 'like', "%{$search}%")
                         ->orWhere('lname', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('user_name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->get();

        // Calculate KPI Stats
        $stats = [
            'total_revenue'         => PaymentTransaction::paid()->sum('amount'),
            'success_count'         => PaymentTransaction::paid()->count(),
            'failed_count'          => PaymentTransaction::failed()->count(),
            'pending_count'         => PaymentTransaction::where('status', 'pending')->count(),
            'total_games_purchased' => PaymentTransaction::paid()->sum('games_count'),
            'total_coins_purchased' => PaymentTransaction::paid()->sum('coins_count'),
            'total_transactions'    => PaymentTransaction::count(),
        ];

        return view('admin.financial_reports.all_transactions', compact('transactions', 'stats'));
    }

    /**
     * Get transaction details for modal (JSON)
     */
    public function transactionDetails($id)
    {
        $transaction = PaymentTransaction::with(['user', 'price', 'gameCoin'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'العملية غير موجودة',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $transaction,
        ]);
    }

    /**
     * Send direct Push Notification to the user from the financial transactions screen
     */
    public function sendNotificationToUser(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ], [
            'title.required'       => 'الرجاء إدخال عنوان الإشعار.',
            'description.required' => 'الرجاء إدخال نص الإشعار.',
            'user_id.required'     => 'المستخدم غير محدد.',
        ]);

        $title = $request->title;
        $descriptionHtml = $request->description;
        $descriptionPlain = strip_tags($descriptionHtml);
        $userId = $request->user_id;

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.',
            ], 404);
        }

        $sentFirebase = false;
        $errorMessage = '';

        // 1. Save locally in notification_for_apps
        try {
            DB::table('notification_for_apps')->insert([
                'user_id'    => $user->id,
                'title'      => $title,
                'des'        => $descriptionHtml,
                'user_view'  => 'no',
                'date'       => now()->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->notify(new AdminPushNotification($title, $descriptionPlain));
        } catch (\Exception $e) {
            \Log::error('Notification local save error: ' . $e->getMessage());
        }

        // 2. Try sending Firebase Push
        $token = $user->fcm_token ?: $user->firebase_token;
        if (!empty($token) && strlen($token) >= 20) {
            try {
                $messaging = Firebase::messaging();

                $userBadge = DB::table('notification_for_apps')
                    ->where('user_id', $user->id)
                    ->where('user_view', 'no')
                    ->count();

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
                    ->withToken($token)
                    ->withNotification($notification)
                    ->withAndroidConfig($androidConfig)
                    ->withApnsConfig($apnsConfig)
                    ->withData(['badge' => (string)$userBadge]);

                $messaging->send($message);
                $sentFirebase = true;
            } catch (\Exception $e) {
                \Log::warning('FCM send failed for user ' . $user->id . ': ' . $e->getMessage());
                $errorMessage = $e->getMessage();
            }
        }

        return response()->json([
            'success'       => true,
            'sent_firebase' => $sentFirebase,
            'message'       => $sentFirebase 
                ? 'تم إرسال الإشعار للمستخدم بنجاح عبر Firebase وحفظه محلياً.'
                : 'تم حفظ الإشعار في حساب المستخدم بنجاح (المستخدم ليس لديه توكن نشط).',
        ]);
    }

    /**
     * Export Transactions to Excel
     */
    public function exportTransactions(Request $request)
    {
        $fileName = 'financial_transactions_' . date('Y_m_d_His') . '.xlsx';
        return Excel::download(new FinancialReportExport($request), $fileName);
    }
}

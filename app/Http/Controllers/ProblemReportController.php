<?php

namespace App\Http\Controllers;

use App\Models\ProblemReport;
use App\Models\User;
use App\Notifications\AdminPushNotification;
use Illuminate\Http\Request;
use App\Exports\ProblemReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;

class ProblemReportController extends Controller
{
    public function allProblemReports(Request $request)
    {
        $query = ProblemReport::with(['user', 'question', 'cheatingUser']);

        // Issue Type Filter
        if ($request->filled('issue_type') && $request->issue_type !== 'all') {
            $query->where('issue_type', $request->issue_type);
        }

        // Report Type Filter (مصدر المشكلة)
        if ($request->filled('report_type') && $request->report_type !== 'all') {
            $query->where('report_type', $request->report_type);
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        if ($request->sort_by === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $reports = $query->get();
        return view('admin.problem_report.all_problem_reports', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,ignored'
        ], [
            'status.required' => '⚠️ الحقل مطلوب',
            'status.in' => '⚠️ الحالة غير صالحة',
        ]);

        $report = ProblemReport::findOrFail($id);
        $report->update([
            'status' => $request->status
        ]);

        $notifSent = false;
        if ($request->has('send_notification') && $request->send_notification == '1' && $request->filled('notification_title')) {
            $this->sendNotificationToUser(
                $report->user_id,
                $request->notification_title,
                $request->notification_details ?? '',
                (int)($request->badge ?? 1)
            );
            $notifSent = true;
        }

        $notification = array(
            'message' => 'تم تحديث حالة البلاغ بنجاح' . ($notifSent ? ' وإرسال الإشعار للمستخدم.' : '.'),
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function sendNotificationToUser($userId, $title, $descriptionHtml, $badge = 1)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $descriptionPlain = strip_tags($descriptionHtml ?: $title);

        // 1. Laravel Database Notification
        try {
            $user->notify(new AdminPushNotification($title, $descriptionPlain));
        } catch (\Exception $dbEx) {
            Log::error('ProblemReport Notification Save Error: ' . $dbEx->getMessage());
        }

        // 2. Firebase Cloud Messaging (FCM Push Notification)
        try {
            $token = $user->fcm_token ?: $user->firebase_token;

            if ($token && strlen($token) >= 20) {
                $messaging = Firebase::messaging();
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
                            'badge' => $badge,
                        ],
                    ],
                ]);

                $message = CloudMessage::new()
                    ->withToken($token)
                    ->withNotification($notification)
                    ->withAndroidConfig($androidConfig)
                    ->withApnsConfig($apnsConfig)
                    ->withData(['badge' => (string) $badge]);

                $messaging->send($message);
            }
        } catch (\Exception $e) {
            Log::error('ProblemReport Firebase Notification Error: ' . $e->getMessage());
        }

        // 3. Insert into notification_for_apps table
        try {
            $now = now();
            DB::table('notification_for_apps')->insert([
                'user_id'    => $user->id,
                'title'      => $title,
                'des'        => $descriptionHtml ?: $title,
                'user_view'  => 'no',
                'date'       => $now->toDateTimeString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } catch (\Exception $appDbEx) {
            Log::error('ProblemReport notification_for_apps Save Error: ' . $appDbEx->getMessage());
        }
    }

    public function deleteProblemReport($id)
    {
        $report = ProblemReport::findOrFail($id);
        $report->delete();

        $notification = array(
            'message' => 'تم حذف البلاغ بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function storeProblemReportApi(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required_unless:issue_type,cheating|nullable|exists:questions,id',
            'issue_type' => 'required|in:question_error,answer_error,inappropriate_content,cheating',
            'user_id_cheating' => 'required_if:issue_type,cheating|nullable|exists:users,id',
            'report_type' => 'nullable|in:question,answer',
            'additional_notes' => 'nullable|string',
        ], [
            'user_id.required' => 'حقل معرف المستخدم مطلوب.',
            'user_id.exists' => 'المستخدم المحدد غير موجود.',
            'question_id.required_unless' => 'حقل معرف السؤال مطلوب.',
            'question_id.exists' => 'السؤال المحدد غير موجود.',
            'issue_type.required' => 'نوع المشكلة مطلوب.',
            'issue_type.in' => 'نوع المشكلة غير صالح.',
            'user_id_cheating.required_if' => 'حقل معرف المستخدم الذي غش مطلوب لحالة الغش.',
            'user_id_cheating.exists' => 'المستخدم المحدد للغش غير موجود.',
            'report_type.in' => 'حقل report_type يجب أن يكون question أو answer.',
            'additional_notes.string' => 'الملاحظات الإضافية يجب أن تكون نصاً.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $report = ProblemReport::create([
            'user_id' => $request->user_id,
            'question_id' => $request->issue_type === 'cheating' ? null : $request->question_id,
            'user_id_cheating' => $request->issue_type === 'cheating' ? $request->user_id_cheating : null,
            'issue_type' => $request->issue_type,
            'report_type' => $request->report_type ?? 'question',
            'additional_notes' => $request->additional_notes,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل البلاغ بنجاح',
            'data' => $report
        ], 201);
    }

    public function exportProblemReports(Request $request)
    {
        return Excel::download(new ProblemReportExport($request), 'problem_reports_' . date('Y_m_d_His') . '.xlsx');
    }
}

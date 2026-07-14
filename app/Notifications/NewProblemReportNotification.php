<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProblemReportNotification extends Notification
{
    use Queueable;

    protected $problemReport;

    /**
     * Create a new notification instance.
     */
    public function __construct($problemReport)
    {
        $this->problemReport = $problemReport;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $user = $this->problemReport->user;
        $name = '';
        if ($user) {
            $name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
            if (empty($name)) {
                $name = $user->user_name ?? $user->email ?? 'عضو';
            }
        } else {
            $name = 'عضو غير معروف';
        }

        $issueType = $this->problemReport->issue_type;
        $issueTypeArabic = 'بلاغ عام';
        if ($issueType === 'question_error') {
            $issueTypeArabic = 'خطأ في السؤال';
        } elseif ($issueType === 'answer_error') {
            $issueTypeArabic = 'خطأ في الإجابة';
        } elseif ($issueType === 'inappropriate_content') {
            $issueTypeArabic = 'محتوى غير لائق';
        } elseif ($issueType === 'cheating') {
            $issueTypeArabic = 'حالة غش';
        }

        $data = [
            'type' => 'بلاغ جديد عن مشكلة',
            'message' => 'نوع المشكلة: ' . $issueTypeArabic,
            'senderName' => $name,
            'gameName' => 'لا يوجد',
            'user_photo' => $user ? $user->photo : null,
            'user_id' => $user ? $user->id : null,
            'report_id' => $this->problemReport->id,
        ];

        if ($issueType === 'cheating') {
            $data['user_id_cheating'] = $this->problemReport->user_id_cheating;
        }

        return $data;
    }
}

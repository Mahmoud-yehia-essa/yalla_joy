<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserRegisterNotification extends Notification
{
    use Queueable;

    protected $newUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($newUser)
    {
        $this->newUser = $newUser;
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
        $name = trim(($this->newUser->fname ?? '') . ' ' . ($this->newUser->lname ?? ''));
        if (empty($name)) {
            $name = $this->newUser->user_name ?? $this->newUser->email ?? 'عضو جديد';
        }

        return [
            'type' => 'تسجيل عضو جديد',
            'message' => 'لقد تم تسجيل عضو جديد في النظام.',
            'senderName' => $name,
            'gameName' => 'لا يوجد',
            'user_id' => $this->newUser->id,
            'user_photo' => $this->newUser->photo,
        ];
    }
}

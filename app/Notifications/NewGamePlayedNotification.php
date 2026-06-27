<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewGamePlayedNotification extends Notification
{
    use Queueable;

    protected $game;

    /**
     * Create a new notification instance.
     */
    public function __construct($game)
    {
        $this->game = $game;
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
        $user = $this->game->users;
        $name = '';
        if ($user) {
            $name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
            if (empty($name)) {
                $name = $user->user_name ?? $user->email ?? 'عضو';
            }
        } else {
            $name = 'عضو غير معروف';
        }

        return [
            'type' => 'لعب لعبة جديدة',
            'message' => 'لقد تم لعب لعبة جديدة بنجاح.',
            'senderName' => $name,
            'gameName' => $this->game->game_name ?? 'بدون اسم',
            'user_id' => $user ? $user->id : null,
            'user_photo' => $user ? $user->photo : null,
            'game_id' => $this->game->id,
        ];
    }
}

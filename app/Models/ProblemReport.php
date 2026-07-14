<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemReport extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function cheatingUser()
    {
        return $this->belongsTo(User::class, 'user_id_cheating');
    }

    protected static function booted()
    {
        static::created(function ($problemReport) {
            if (!app()->runningInConsole()) {
                try {
                    $admins = User::where('role', 'admin')->where('notify_problem_report', true)->get();
                    if ($admins->count() > 0) {
                        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewProblemReportNotification($problemReport));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send problem report notification: ' . $e->getMessage());
                }
            }
        });
    }
}

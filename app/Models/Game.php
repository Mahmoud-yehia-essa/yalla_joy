<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;
use App\Models\GamesCategories;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function gamesCategories()
    {
        return $this->hasMany(GamesCategories::class, 'game_id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'games_categories', 'game_id', 'category_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id_winner');
    }

    protected static function booted()
    {
        static::created(function ($game) {
            if (!app()->runningInConsole()) {
                try {
                    $admins = User::where('role', 'admin')->where('notify_game_played', true)->get();
                    if ($admins->count() > 0) {
                        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewGamePlayedNotification($game));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send game played notification: ' . $e->getMessage());
                }
            }
        });
    }




}

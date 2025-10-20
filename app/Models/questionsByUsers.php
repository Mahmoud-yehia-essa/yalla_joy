<?php

namespace App\Models;

use App\Models\UserGame;
use App\Models\UserQuestionAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class questionsByUsers extends Model
{
    use HasFactory;
        protected $guarded = [];

    // protected $fillable = [
    //     'user_id',
    //     'user_game_id',
    //     'qu_title',
    //     'qu_image',
    //     'qu_sound',
    //     'qu_video',
    //     'qu_points',
    //     'time_counter',
    //     'questions_type',
    // ];

    public function game()
    {
        return $this->belongsTo(UserGame::class, 'user_game_id');
    }

    public function answers()
    {
        return $this->hasMany(UserQuestionAnswer::class, 'questions_by_user_id');
    }
}

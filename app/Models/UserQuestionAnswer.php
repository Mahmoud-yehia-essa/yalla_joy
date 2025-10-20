<?php

namespace App\Models;

use App\Models\questionsByUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserQuestionAnswer extends Model
{
    use HasFactory;

            protected $guarded = [];

    // protected $fillable = [
    //     'questions_by_user_id',
    //     'answer_title',
    //     'answer_image',
    //     'answer_sound',
    //     'answer_video',
    //     'answer_type',
    // ];

    public function question()
    {
        return $this->belongsTo(questionsByUsers::class, 'questions_by_user_id');
    }
}

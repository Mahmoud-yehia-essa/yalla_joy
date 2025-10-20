<?php

namespace App\Models;

use App\Models\Category;
use App\Models\GameType;
use App\Models\MainCategory;
use App\Models\AnswerQuestionOnline;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $guarded = [];


    // Relationship: A question can have many answers
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }



       public function answerQuestionOnlines()
    {
        return $this->hasMany(AnswerQuestionOnline::class, 'question_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


        public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }



         public function gameType()
    {
        return $this->belongsTo(GameType::class, 'game_type_id');
    }
}

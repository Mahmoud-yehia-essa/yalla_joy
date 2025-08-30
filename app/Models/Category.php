<?php

namespace App\Models;

use App\Models\GameType;
use App\Models\Question;
use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Question::class, 'category_id');
    }


     public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }


      public function gameType()
    {
        return $this->belongsTo(GameType::class, 'game_type_id');
    }

    //
}

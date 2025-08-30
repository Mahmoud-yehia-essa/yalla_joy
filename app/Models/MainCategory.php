<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
            protected $guarded = [];

    public function gameType()
    {
        return $this->belongsTo(GameType::class, 'game_type_id');
    }

      public function categories()
    {
        return $this->hasMany(Category::class, 'main_category_id');
    }




}

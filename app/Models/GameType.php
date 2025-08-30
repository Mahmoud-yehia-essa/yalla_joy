<?php

namespace App\Models;

use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
        protected $guarded = [];


            public function mainCategories()
    {
        return $this->hasMany(MainCategory::class, 'game_type_id');
    }

}

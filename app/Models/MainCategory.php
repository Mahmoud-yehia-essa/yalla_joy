<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
            protected $guarded = [];

    public function gameType()
    {
        return $this->belongsTo(GameType::class, 'game_type_id');
    }

}

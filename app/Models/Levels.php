<?php

namespace App\Models;

use App\Models\GameCoin;
use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{


             protected $guarded = [];

    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class);
    }
}

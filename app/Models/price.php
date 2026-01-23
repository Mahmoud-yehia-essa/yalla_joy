<?php

namespace App\Models;

use App\Models\GameCoin;
use Illuminate\Database\Eloquent\Model;

class price extends Model
{
        protected $guarded = [];

    //      public function getPriceAttribute($value)
    // {
    //     return rtrim(rtrim($value, '0'), '.');
    // }


     public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class);
    }

}

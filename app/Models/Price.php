<?php

namespace App\Models;

use App\Models\GameCoin;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $guarded = [];

    // public function getPriceAttribute($value)
    // {
    //     return rtrim(rtrim($value, '0'), '.');
    // }

    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class);
    }
}

if (!class_exists('App\Models\price', false)) {
    class_alias(Price::class, 'App\Models\price');
}

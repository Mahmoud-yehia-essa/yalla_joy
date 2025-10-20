<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameItem extends Model
{
    protected $guarded = [];

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class);
    }
}


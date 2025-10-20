<?php

namespace App\Models;

use App\Models\GameCoin;
use Illuminate\Database\Eloquent\Model;

class GameBundleCoin extends Model
{
            protected $guarded = [];

    public function coin()
{
    return $this->belongsTo(GameCoin::class, 'game_coin_id', 'id');
}

}

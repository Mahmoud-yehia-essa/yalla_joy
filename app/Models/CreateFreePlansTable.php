<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateFreePlansTable extends Model
{
        protected $guarded = [];

        public function gameCoin()
        {
            return $this->belongsTo(GameCoin::class, 'game_coin_id');
        }

}

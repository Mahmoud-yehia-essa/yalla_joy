<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingNew extends Model
{
    protected $table = 'rankings_new';
    protected $guarded = [];

    public function rankRewardCoin()
    {
        return $this->belongsTo(GameCoin::class, 'rank_reward_coin_id');
    }

    public function levelRewardCoin()
    {
        return $this->belongsTo(GameCoin::class, 'level_reward_coin_id');
    }
}

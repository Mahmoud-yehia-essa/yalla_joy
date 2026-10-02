<?php

namespace App\Models;

use App\Models\User;
use App\Models\MainCategory;
use App\Models\GameCoin;
use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
    protected $guarded = [];

    public function mainCategories()
    {
        return $this->hasMany(MainCategory::class, 'game_type_id');
    }

    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'game_coin_id');
    }

    public function offlineGameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'offline_game_coin_id');
    }

    public function onlineSearchGameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'online_search_game_coin_id');
    }

    public function onlineCreateGameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'online_create_game_coin_id');
    }

    public function onlineChallengeGameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'online_challenge_game_coin_id');
    }

    public function topScorersGameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'top_scorers_game_coin_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCoupon extends Model
{
    protected $guarded = [];

    public function gamePurchase()
    {
        return $this->belongsTo(GamePurchase::class, 'game_purchases_id');
    }

    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'game_coin_id');
    }

    public function usages()
    {
        return $this->hasMany(GameCouponUsage::class, 'game_coupon_id');
    }
}

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
}

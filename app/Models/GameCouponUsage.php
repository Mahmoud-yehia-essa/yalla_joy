<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCouponUsage extends Model
{
    protected $guarded = [];

    public function gameCoupon()
    {
        return $this->belongsTo(GameCoupon::class, 'game_coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

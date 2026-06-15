<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCompany extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the sponsor that owns the coupon company.
     */
    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    /**
     * Get the game coin that owns the coupon company.
     */
    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class);
    }
}

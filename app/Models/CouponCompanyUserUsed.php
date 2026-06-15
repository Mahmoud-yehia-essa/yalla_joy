<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCompanyUserUsed extends Model
{
    use HasFactory;

    protected $table = 'coupon_companies_users_used';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function couponCompany()
    {
        return $this->belongsTo(CouponCompany::class, 'coupon_companie_id');
    }
}

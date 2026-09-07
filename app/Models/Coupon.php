<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $guarded = [];
}

if (!class_exists('App\Models\coupon', false)) {
    class_alias(Coupon::class, 'App\Models\coupon');
}

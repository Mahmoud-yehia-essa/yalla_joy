<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coupon_companies_users_used', function (Blueprint $table) {
            $table->boolean('is_used')->default(0);
            $table->boolean('is_buy')->default(1);
            $table->dateTime('used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_companies_users_used', function (Blueprint $table) {
            $table->dropColumn(['is_used', 'is_buy', 'used_at']);
        });
    }
};

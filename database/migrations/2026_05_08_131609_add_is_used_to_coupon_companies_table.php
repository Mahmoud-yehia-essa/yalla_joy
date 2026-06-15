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
        Schema::table('coupon_companies', function (Blueprint $table) {
            $table->boolean('is_used')->default(0)->after('game_coins_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_companies', function (Blueprint $table) {
            $table->dropColumn('is_used');
        });
    }
};

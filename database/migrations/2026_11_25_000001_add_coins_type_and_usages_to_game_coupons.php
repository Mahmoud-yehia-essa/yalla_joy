<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. توسيع enum نوع الكوبون لإضافة نوع كسب العملات (coins)
        DB::statement("ALTER TABLE `game_coupons` MODIFY COLUMN `type` ENUM('percentage', 'free_games', 'package_bonus', 'coins') NOT NULL");

        // 2. إضافة حقول عدد العملات ونوع العملة في جدول game_coupons
        Schema::table('game_coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('game_coupons', 'coins_count')) {
                $table->integer('coins_count')->nullable()->after('free_games_count');
            }
            if (!Schema::hasColumn('game_coupons', 'game_coin_id')) {
                $table->foreignId('game_coin_id')
                      ->nullable()
                      ->after('coins_count')
                      ->constrained('game_coins')
                      ->onDelete('set null');
            }
        });

        // 3. إنشاء جدول تسجيل استخدامات الكوبونات لمنع المستخدم من استخدام نفس الكوبون أكثر من مرة أبداً
        if (!Schema::hasTable('game_coupon_usages')) {
            Schema::create('game_coupon_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('game_coupon_id')->constrained('game_coupons')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['game_coupon_id', 'user_id'], 'game_coupon_user_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_coupon_usages');

        Schema::table('game_coupons', function (Blueprint $table) {
            if (Schema::hasColumn('game_coupons', 'game_coin_id')) {
                $table->dropForeign(['game_coin_id']);
                $table->dropColumn('game_coin_id');
            }
            if (Schema::hasColumn('game_coupons', 'coins_count')) {
                $table->dropColumn('coins_count');
            }
        });

        DB::statement("ALTER TABLE `game_coupons` MODIFY COLUMN `type` ENUM('percentage', 'free_games', 'package_bonus') NOT NULL");
    }
};

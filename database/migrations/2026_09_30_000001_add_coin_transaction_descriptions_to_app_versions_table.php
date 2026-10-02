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
        Schema::table('app_versions', function (Blueprint $table) {
            if (!Schema::hasColumn('app_versions', 'coin_desc_online_purchase')) {
                $table->string('coin_desc_online_purchase')->nullable()->default('شراء باقة عملات عبر الدفع الإلكتروني');
            }
            if (!Schema::hasColumn('app_versions', 'coin_desc_game_win')) {
                $table->string('coin_desc_game_win')->nullable()->default('مكافأة الفوز في التحدي');
            }
            if (!Schema::hasColumn('app_versions', 'coin_desc_rank_upgrade')) {
                $table->string('coin_desc_rank_upgrade')->nullable()->default('مكافأة الترقية إلى رتبة جديدة');
            }
            if (!Schema::hasColumn('app_versions', 'coin_desc_coupon_exchange')) {
                $table->string('coin_desc_coupon_exchange')->nullable()->default('استبدال عملات بكوبون خصم');
            }
            if (!Schema::hasColumn('app_versions', 'coin_desc_avatar_purchase')) {
                $table->string('coin_desc_avatar_purchase')->nullable()->default('شراء عنصر من متجر الأفاتار');
            }
            if (!Schema::hasColumn('app_versions', 'coin_desc_animation_purchase')) {
                $table->string('coin_desc_animation_purchase')->nullable()->default('شراء حركة تفاعلية من متجر الحركات');
            }
            if (!Schema::hasColumn('app_versions', 'coin_desc_admin_adjustment')) {
                $table->string('coin_desc_admin_adjustment')->nullable()->default('تعديل رصيد من إدارة التطبيق');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_versions', function (Blueprint $table) {
            $columns = [
                'coin_desc_online_purchase',
                'coin_desc_game_win',
                'coin_desc_rank_upgrade',
                'coin_desc_coupon_exchange',
                'coin_desc_avatar_purchase',
                'coin_desc_animation_purchase',
                'coin_desc_admin_adjustment',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('app_versions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

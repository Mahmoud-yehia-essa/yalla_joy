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
        Schema::table('game_types', function (Blueprint $table) {
            // 1. لعبة الجلسة (Offline / Session)
            if (!Schema::hasColumn('game_types', 'offline_game_coin_id')) {
                $table->foreignId('offline_game_coin_id')->nullable()->constrained('game_coins')->nullOnDelete();
            }
            if (!Schema::hasColumn('game_types', 'offline_coins_number')) {
                $table->integer('offline_coins_number')->default(0)->nullable();
            }

            // 2. لعبة الميدان - البحث عن لاعبين (Matchmaking / Online Search)
            if (!Schema::hasColumn('game_types', 'online_search_game_coin_id')) {
                $table->foreignId('online_search_game_coin_id')->nullable()->constrained('game_coins')->nullOnDelete();
            }
            if (!Schema::hasColumn('game_types', 'online_search_coins_number')) {
                $table->integer('online_search_coins_number')->default(0)->nullable();
            }

            // 3. لعبة الميدان - إنشاء لعبة كاملة (6 فئات)
            if (!Schema::hasColumn('game_types', 'online_create_game_coin_id')) {
                $table->foreignId('online_create_game_coin_id')->nullable()->constrained('game_coins')->nullOnDelete();
            }
            if (!Schema::hasColumn('game_types', 'online_create_coins_number')) {
                $table->integer('online_create_coins_number')->default(0)->nullable();
            }

            // 4. لعبة الميدان - تحدي مع صديق (3 فئات)
            if (!Schema::hasColumn('game_types', 'online_challenge_game_coin_id')) {
                $table->foreignId('online_challenge_game_coin_id')->nullable()->constrained('game_coins')->nullOnDelete();
            }
            if (!Schema::hasColumn('game_types', 'online_challenge_coins_number')) {
                $table->integer('online_challenge_coins_number')->default(0)->nullable();
            }

            // 5. التحديات - نافس الحاصلين على أعلى الدرجات / البحث عن منافس
            if (!Schema::hasColumn('game_types', 'top_scorers_game_coin_id')) {
                $table->foreignId('top_scorers_game_coin_id')->nullable()->constrained('game_coins')->nullOnDelete();
            }
            if (!Schema::hasColumn('game_types', 'top_scorers_coins_number')) {
                $table->integer('top_scorers_coins_number')->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_types', function (Blueprint $table) {
            $fkColumns = [
                'offline_game_coin_id',
                'online_search_game_coin_id',
                'online_create_game_coin_id',
                'online_challenge_game_coin_id',
                'top_scorers_game_coin_id',
            ];
            foreach ($fkColumns as $fk) {
                if (Schema::hasColumn('game_types', $fk)) {
                    $table->dropForeign([$fk]);
                    $table->dropColumn($fk);
                }
            }

            $intColumns = [
                'offline_coins_number',
                'online_search_coins_number',
                'online_create_coins_number',
                'online_challenge_coins_number',
                'top_scorers_coins_number',
            ];
            foreach ($intColumns as $col) {
                if (Schema::hasColumn('game_types', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

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
            if (!Schema::hasColumn('game_types', 'game_coin_id')) {
                $table->foreignId('game_coin_id')->nullable()->after('is_term')->constrained('game_coins')->nullOnDelete();
            }
            if (!Schema::hasColumn('game_types', 'coins_number')) {
                $table->integer('coins_number')->default(0)->nullable()->after('game_coin_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_types', function (Blueprint $table) {
            if (Schema::hasColumn('game_types', 'game_coin_id')) {
                $table->dropForeign(['game_coin_id']);
                $table->dropColumn('game_coin_id');
            }
            if (Schema::hasColumn('game_types', 'coins_number')) {
                $table->dropColumn('coins_number');
            }
        });
    }
};

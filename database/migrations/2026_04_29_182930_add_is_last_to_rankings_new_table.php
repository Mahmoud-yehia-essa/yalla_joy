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
        Schema::table('rankings_new', function (Blueprint $table) {
            $table->tinyInteger('is_last')->default(0)->after('level_reward_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rankings_new', function (Blueprint $table) {
            $table->dropColumn('is_last');
        });
    }
};

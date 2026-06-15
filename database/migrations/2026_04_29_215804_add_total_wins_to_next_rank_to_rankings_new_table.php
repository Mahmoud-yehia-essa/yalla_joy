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
            $table->integer('total_wins_to_next_rank')->default(0)->after('wins_to_next_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rankings_new', function (Blueprint $table) {
            $table->dropColumn('total_wins_to_next_rank');
        });
    }
};

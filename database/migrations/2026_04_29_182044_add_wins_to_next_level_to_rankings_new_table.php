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
            $table->integer('wins_to_next_level')->default(0)->after('levels_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rankings_new', function (Blueprint $table) {
            $table->dropColumn('wins_to_next_level');
        });
    }
};

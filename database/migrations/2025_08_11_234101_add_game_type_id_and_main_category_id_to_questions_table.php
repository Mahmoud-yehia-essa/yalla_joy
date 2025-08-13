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
         Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('game_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('main_category_id')->nullable()->after('game_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['game_type_id', 'main_category_id']);
        });
    }
};

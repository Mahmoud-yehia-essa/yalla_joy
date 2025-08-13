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
       Schema::table('main_categories', function (Blueprint $table) {
            // Only add the column if it doesn't already exist
            if (!Schema::hasColumn('main_categories', 'game_type_id')) {
                $table->foreignId('game_type_id')
                    ->nullable()
                    ->constrained('game_types')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('main_categories', function (Blueprint $table) {
            $table->dropForeign(['game_type_id']);
            $table->dropColumn('game_type_id');
        });
    }
};

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
         Schema::table('categories', function (Blueprint $table) {
            // If your referenced ids are BIGINT unsigned (created with $table->id()):
            $table->foreignId('game_type_id')->nullable()->constrained('game_types')->cascadeOnDelete();
            $table->foreignId('main_category_id')->nullable()->constrained('main_categories')->cascadeOnDelete();

            // If you DO NOT want cascade on delete, remove ->cascadeOnDelete()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // dropForeign takes column name(s) as array
            $table->dropForeign(['game_type_id']);
            $table->dropColumn('game_type_id');

            $table->dropForeign(['main_category_id']);
            $table->dropColumn('main_category_id');
        });
    }
};

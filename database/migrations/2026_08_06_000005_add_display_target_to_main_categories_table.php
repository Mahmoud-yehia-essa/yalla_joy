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
            $table->string('display_target')->default('both')->nullable()->after('main_category_description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_categories', function (Blueprint $table) {
            $table->dropColumn('display_target');
        });
    }
};

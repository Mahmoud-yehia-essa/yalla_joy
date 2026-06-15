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
        Schema::table('proverbs', function (Blueprint $table) {
            $table->unsignedBigInteger('ranking_new_id')->nullable()->after('title_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proverbs', function (Blueprint $table) {
            $table->dropColumn('ranking_new_id');
        });
    }
};

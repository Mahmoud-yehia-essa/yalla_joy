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
            $table->enum('type', ['positive', 'negative'])->default('positive')->after('title_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proverbs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

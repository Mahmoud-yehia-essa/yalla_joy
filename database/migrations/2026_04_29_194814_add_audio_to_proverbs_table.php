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
            $table->string('audio_ar')->nullable()->after('ranking_new_id');
            $table->string('audio_en')->nullable()->after('audio_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proverbs', function (Blueprint $table) {
            $table->dropColumn(['audio_ar', 'audio_en']);
        });
    }
};

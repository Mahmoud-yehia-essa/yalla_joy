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
        Schema::table('challenges', function (Blueprint $table) {
            // حقل موعد فتح باب الانضمام
            $table->dateTime('join_start_at')->nullable()->comment('تاريخ ووقت فتح باب الانضمام للمنافسة');
            
            // حقل موعد غلق باب الانضمام
            $table->dateTime('join_end_at')->nullable()->comment('تاريخ ووقت غلق باب الانضمام للمنافسة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            //
        });
    }
};

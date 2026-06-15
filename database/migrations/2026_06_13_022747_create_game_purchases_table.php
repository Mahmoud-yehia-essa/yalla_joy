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
        Schema::create('game_purchases', function (Blueprint $table) {
            $table->id();
          // عدد الألعاب التي سوف يتم شرائها من قبل المستخدم
            $table->integer('games_count')->default(1); 
            
            // السعر (استخدام decimal أفضل للأسعار والعملات لضمان الدقة وتجنب الكسور التقريبية)
            // الرقم 8 يعني الحد الأقصى للمخان هو 8 أرقام، و 2 يعني رقمين بعد الفاصلة (مثلاً: 99.99)
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_purchases');
    }
};

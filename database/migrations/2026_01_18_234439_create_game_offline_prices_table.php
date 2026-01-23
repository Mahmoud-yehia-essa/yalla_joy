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
        Schema::create('game_offline_prices', function (Blueprint $table) {
            $table->id();
             $table->string('title');
           $table->foreignId('game_coin_id')->nullable()->constrained('game_coins')->cascadeOnDelete();
            $table->integer('coins_number')->nullable();
            $table->text('color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_offline_prices');
    }
};

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
        Schema::create('point_with_coins', function (Blueprint $table) {
             $table->id();
                         $table->enum('type',['offline','online'])->default('offline');

                $table->foreignId('game_coin_id')->nullable()->constrained('game_coins')->cascadeOnDelete();
            $table->integer('coins_number')->nullable();
                        $table->integer('points_number')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_with_coins');
    }
};

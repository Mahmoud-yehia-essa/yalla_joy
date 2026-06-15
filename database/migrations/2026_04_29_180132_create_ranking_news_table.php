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
        Schema::create('rankings_new', function (Blueprint $table) {
            $table->id();
            $table->string('rank_name')->nullable();
            $table->string('rank_name_en')->nullable();
            $table->text('rank_description')->nullable();
            $table->text('rank_description_en')->nullable();
            $table->integer('rank_order')->nullable();
            $table->unsignedBigInteger('rank_reward_coin_id')->nullable();
            $table->integer('rank_reward_amount')->nullable();
            $table->integer('levels_count')->default(0);
            $table->unsignedBigInteger('level_reward_coin_id')->nullable();
            $table->integer('level_reward_amount')->nullable();
            $table->timestamps();
            
            // Note: Foreign key constraints are optional depending on existing DB structure.
            // But we will add them for integrity if needed, or simply let eloquent handle the logic.
            // $table->foreign('rank_reward_coin_id')->references('id')->on('game_coins')->onDelete('set null');
            // $table->foreign('level_reward_coin_id')->references('id')->on('game_coins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings_new');
    }
};

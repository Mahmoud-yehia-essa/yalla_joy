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
        Schema::create('game_session_question_onlines', function (Blueprint $table) {


         $table->id();
    $table->string('session_id');
    $table->unsignedBigInteger('category_id');
    $table->unsignedBigInteger('question_id');
    $table->integer('question_order')->default(0);
    $table->timestamps();

    $table->index(['session_id', 'category_id']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_session_question_onlines');
    }
};

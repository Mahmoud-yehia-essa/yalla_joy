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
        Schema::create('online_game_infos', function (Blueprint $table) {
            $table->id();
           $table->foreignId('created_user_id')->nullable()->constrained('users')->cascadeOnDelete();

             $table->integer('users_count')->nullable();
            $table->text('game_session_name')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_game_infos');
    }
};

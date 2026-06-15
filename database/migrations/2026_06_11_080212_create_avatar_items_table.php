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
        Schema::create('avatar_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
$table->string('image')->nullable();
$table->foreignId('category_id')->nullable()->constrained('avatar_categories')->onDelete('cascade');
    $table->foreignId('game_coin_id')->nullable()->constrained('game_coins')->cascadeOnDelete();
            $table->integer('coins_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avatar_items');
    }
};

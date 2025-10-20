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
        Schema::create('game_bundle_items', function (Blueprint $table) {
            $table->id();

             $table->foreignId('game_bundle_id')->nullable()->constrained('game_bundles')->cascadeOnDelete();
            $table->foreignId('game_item_id')->nullable()->constrained('game_items')->cascadeOnDelete();
            $table->integer('number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_bundle_items');
    }
};

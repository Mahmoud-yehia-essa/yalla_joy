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
        Schema::create('title_positions', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->text('photo')->nullable();
            $table->integer('coins')->nullable();;
            $table->integer('points')->nullable();;



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_positions');
    }
};

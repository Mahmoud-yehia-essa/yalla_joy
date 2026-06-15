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
        Schema::create('coupon_companies', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_name')->nullable();
            $table->string('coupon_name_en')->nullable();
            $table->text('coupon_description')->nullable();
            $table->text('coupon_description_en')->nullable();
            $table->string('coupon_code')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->foreignId('sponsor_id')->nullable()->constrained('sponsors')->onDelete('cascade');
            $table->boolean('is_scratch_coupon')->default(false);
            $table->foreignId('game_coin_id')->nullable()->constrained('game_coins')->onDelete('cascade');
            $table->integer('game_coins_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_companies');
    }
};

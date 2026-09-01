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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('order_no')->unique()->index();
            $table->string('session_id')->nullable()->index();
            $table->string('package_type')->default('games'); // 'games', 'coins', 'price_package'
            $table->unsignedBigInteger('price_id')->nullable()->index();
            $table->string('package_title')->nullable();
            $table->integer('games_count')->default(0);
            $table->integer('coins_count')->default(0);
            $table->unsignedBigInteger('game_coin_id')->nullable()->index();
            $table->decimal('amount', 10, 3)->default(0.000);
            $table->string('currency', 10)->default('KWD');
            $table->string('pg_code', 50)->nullable()->default('knet');
            $table->string('status', 30)->default('pending')->index(); // 'pending', 'paid', 'success', 'failed', 'cancelled'
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->longText('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};

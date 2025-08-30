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
        Schema::create('rewards_sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('title_en')->nullable();

            $table->integer('number_of_points')->nullable();

                $table->text('coupon_name')->nullable();
            $table->string('coupon_validity');

            $table->text('des')->nullable();
                        $table->text('des_en')->nullable();

        $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards_sponsors');
    }
};

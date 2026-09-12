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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'number_of_games')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('number_of_games')->default(1)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'number_of_games')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('number_of_games')->default(0)->change();
            });
        }
    }
};

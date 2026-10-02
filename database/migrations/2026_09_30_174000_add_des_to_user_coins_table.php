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
        Schema::table('user_coins', function (Blueprint $table) {
            if (!Schema::hasColumn('user_coins', 'des')) {
                $table->text('des')->nullable()->after('coins_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_coins', function (Blueprint $table) {
            if (Schema::hasColumn('user_coins', 'des')) {
                $table->dropColumn('des');
            }
        });
    }
};

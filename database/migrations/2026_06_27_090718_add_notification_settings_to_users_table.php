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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_new_user')->default(true)->after('role');
            $table->boolean('notify_problem_report')->default(true)->after('notify_new_user');
            $table->boolean('notify_game_played')->default(true)->after('notify_problem_report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_new_user', 'notify_problem_report', 'notify_game_played']);
        });
    }
};

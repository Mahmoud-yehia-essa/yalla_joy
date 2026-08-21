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
        Schema::table('game_helpers', function (Blueprint $table) {
            if (!Schema::hasColumn('game_helpers', 'tool_key')) {
                $table->string('tool_key')->nullable()->after('name_en');
            }
            if (!Schema::hasColumn('game_helpers', 'note')) {
                $table->string('note')->nullable()->after('description_en');
            }
            if (!Schema::hasColumn('game_helpers', 'use_before_question')) {
                $table->boolean('use_before_question')->default(false)->after('note');
            }
            if (!Schema::hasColumn('game_helpers', 'order_num')) {
                $table->integer('order_num')->default(0)->after('use_before_question');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_helpers', function (Blueprint $table) {
            $table->dropColumn(['tool_key', 'note', 'use_before_question', 'order_num']);
        });
    }
};

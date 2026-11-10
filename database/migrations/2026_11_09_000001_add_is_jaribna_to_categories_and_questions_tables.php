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
        if (Schema::hasTable('main_categories') && !Schema::hasColumn('main_categories', 'is_jaribna')) {
            Schema::table('main_categories', function (Blueprint $table) {
                $table->tinyInteger('is_jaribna')->default(0)->index()->after('display_target');
            });
        }

        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'is_jaribna')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->tinyInteger('is_jaribna')->default(0)->index()->after('display_target');
            });
        }

        if (Schema::hasTable('questions') && !Schema::hasColumn('questions', 'is_jaribna')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->tinyInteger('is_jaribna')->default(0)->index()->after('term');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('main_categories') && Schema::hasColumn('main_categories', 'is_jaribna')) {
            Schema::table('main_categories', function (Blueprint $table) {
                $table->dropColumn('is_jaribna');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'is_jaribna')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('is_jaribna');
            });
        }

        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'is_jaribna')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('is_jaribna');
            });
        }
    }
};


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
        Schema::table('app_versions', function (Blueprint $table) {
            if (!Schema::hasColumn('app_versions', 'app_status')) {
                $table->string('app_status', 50)->default('normal')->after('update_required');
            }
            if (!Schema::hasColumn('app_versions', 'maintenance_title')) {
                $table->string('maintenance_title')->nullable()->after('app_status');
            }
            if (!Schema::hasColumn('app_versions', 'maintenance_message')) {
                $table->text('maintenance_message')->nullable()->after('maintenance_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_versions', function (Blueprint $table) {
            if (Schema::hasColumn('app_versions', 'app_status')) {
                $table->dropColumn('app_status');
            }
            if (Schema::hasColumn('app_versions', 'maintenance_title')) {
                $table->dropColumn('maintenance_title');
            }
            if (Schema::hasColumn('app_versions', 'maintenance_message')) {
                $table->dropColumn('maintenance_message');
            }
        });
    }
};

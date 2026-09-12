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
            if (!Schema::hasColumn('app_versions', 'payment_mode')) {
                $table->string('payment_mode')->default('sandbox')->nullable()->after('app_name');
            }
            if (!Schema::hasColumn('app_versions', 'ottu_live_api_url')) {
                $table->text('ottu_live_api_url')->nullable()->after('payment_mode');
            }
            if (!Schema::hasColumn('app_versions', 'ottu_live_api_key')) {
                $table->text('ottu_live_api_key')->nullable()->after('ottu_live_api_url');
            }
            if (!Schema::hasColumn('app_versions', 'ottu_live_pg_codes')) {
                $table->string('ottu_live_pg_codes')->default('knet')->nullable()->after('ottu_live_api_key');
            }
            if (!Schema::hasColumn('app_versions', 'ottu_sandbox_api_url')) {
                $table->text('ottu_sandbox_api_url')->nullable()->after('ottu_live_pg_codes');
            }
            if (!Schema::hasColumn('app_versions', 'ottu_sandbox_api_key')) {
                $table->text('ottu_sandbox_api_key')->nullable()->after('ottu_sandbox_api_url');
            }
            if (!Schema::hasColumn('app_versions', 'ottu_sandbox_pg_codes')) {
                $table->string('ottu_sandbox_pg_codes')->default('knet')->nullable()->after('ottu_sandbox_api_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_versions', function (Blueprint $table) {
            $columns = [
                'payment_mode',
                'ottu_live_api_url',
                'ottu_live_api_key',
                'ottu_live_pg_codes',
                'ottu_sandbox_api_url',
                'ottu_sandbox_api_key',
                'ottu_sandbox_pg_codes',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('app_versions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

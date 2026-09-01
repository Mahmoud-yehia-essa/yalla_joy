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
            if (!Schema::hasColumn('app_versions', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('font_color_normal');
            }
            if (!Schema::hasColumn('app_versions', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('whatsapp_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_versions', function (Blueprint $table) {
            if (Schema::hasColumn('app_versions', 'whatsapp_number')) {
                $table->dropColumn('whatsapp_number');
            }
            if (Schema::hasColumn('app_versions', 'contact_email')) {
                $table->dropColumn('contact_email');
            }
        });
    }
};

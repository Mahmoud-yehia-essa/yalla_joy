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
            // General mail fallback
            if (!Schema::hasColumn('app_versions', 'mail_from_name')) {
                $table->string('mail_from_name', 150)->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('app_versions', 'mail_from_address')) {
                $table->string('mail_from_address', 150)->nullable()->after('mail_from_name');
            }

            // OTP / Sign up verification email settings
            if (!Schema::hasColumn('app_versions', 'mail_otp_from_name')) {
                $table->string('mail_otp_from_name', 150)->nullable()->after('mail_from_address');
            }
            if (!Schema::hasColumn('app_versions', 'mail_otp_from_address')) {
                $table->string('mail_otp_from_address', 150)->nullable()->after('mail_otp_from_name');
            }
            if (!Schema::hasColumn('app_versions', 'mail_otp_cc')) {
                $table->string('mail_otp_cc', 255)->nullable()->after('mail_otp_from_address');
            }

            // Purchase Invoice email settings
            if (!Schema::hasColumn('app_versions', 'mail_invoice_from_name')) {
                $table->string('mail_invoice_from_name', 150)->nullable()->after('mail_otp_cc');
            }
            if (!Schema::hasColumn('app_versions', 'mail_invoice_from_address')) {
                $table->string('mail_invoice_from_address', 150)->nullable()->after('mail_invoice_from_name');
            }
            if (!Schema::hasColumn('app_versions', 'mail_invoice_cc')) {
                $table->string('mail_invoice_cc', 255)->nullable()->after('mail_invoice_from_address');
            }

            // Coupon / Gifts email settings
            if (!Schema::hasColumn('app_versions', 'mail_coupon_from_name')) {
                $table->string('mail_coupon_from_name', 150)->nullable()->after('mail_invoice_cc');
            }
            if (!Schema::hasColumn('app_versions', 'mail_coupon_from_address')) {
                $table->string('mail_coupon_from_address', 150)->nullable()->after('mail_coupon_from_name');
            }
            if (!Schema::hasColumn('app_versions', 'mail_coupon_cc')) {
                $table->string('mail_coupon_cc', 255)->nullable()->after('mail_coupon_from_address');
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
                'mail_from_name',
                'mail_from_address',
                'mail_otp_from_name',
                'mail_otp_from_address',
                'mail_otp_cc',
                'mail_invoice_from_name',
                'mail_invoice_from_address',
                'mail_invoice_cc',
                'mail_coupon_from_name',
                'mail_coupon_from_address',
                'mail_coupon_cc',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('app_versions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

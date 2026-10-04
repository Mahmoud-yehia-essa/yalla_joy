<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $guarded = [];

    /**
     * Get customized description for a coin transaction with optional placeholders replacement
     */
    public static function getCoinDescription(string $type, string $fallback = '', array $replacements = []): string
    {
        try {
            $setting = self::first();
            $column = 'coin_desc_' . $type;
            $template = ($setting && !empty($setting->{$column})) ? $setting->{$column} : $fallback;

            if (empty($template)) {
                $template = $fallback;
            }

            foreach ($replacements as $key => $value) {
                $template = str_replace('{' . $key . '}', (string)$value, $template);
            }

            return $template;
        } catch (\Exception $e) {
            return $fallback;
        }
    }

    /**
     * Get customized mail configuration (From Address, From Name, and CC List) by notification type
     *
     * @param string $type ('otp', 'invoice', 'coupon', 'general')
     * @return array
     */
    public static function getMailSettings(string $type = 'general'): array
    {
        try {
            $setting = self::first();
            $fallbackFromAddress = ($setting && !empty($setting->mail_from_address)) 
                ? $setting->mail_from_address 
                : config('mail.from.address', 'no-reply@fiktahadi.com');
            $fallbackFromName = ($setting && !empty($setting->mail_from_name)) 
                ? $setting->mail_from_name 
                : config('mail.from.name', 'فيك تحدي / Fiktahadi');

            $fromAddress = $fallbackFromAddress;
            $fromName = $fallbackFromName;
            $ccList = [];

            if ($setting) {
                if ($type === 'otp') {
                    $fromAddress = !empty($setting->mail_otp_from_address) ? $setting->mail_otp_from_address : $fallbackFromAddress;
                    $fromName = !empty($setting->mail_otp_from_name) ? $setting->mail_otp_from_name : $fallbackFromName;
                    if (!empty($setting->mail_otp_cc)) {
                        $ccList = array_values(array_filter(array_map('trim', explode(',', $setting->mail_otp_cc))));
                    }
                } elseif ($type === 'invoice') {
                    $fromAddress = !empty($setting->mail_invoice_from_address) ? $setting->mail_invoice_from_address : $fallbackFromAddress;
                    $fromName = !empty($setting->mail_invoice_from_name) ? $setting->mail_invoice_from_name : $fallbackFromName;
                    if (!empty($setting->mail_invoice_cc)) {
                        $ccList = array_values(array_filter(array_map('trim', explode(',', $setting->mail_invoice_cc))));
                    }
                } elseif ($type === 'coupon') {
                    $fromAddress = !empty($setting->mail_coupon_from_address) ? $setting->mail_coupon_from_address : $fallbackFromAddress;
                    $fromName = !empty($setting->mail_coupon_from_name) ? $setting->mail_coupon_from_name : $fallbackFromName;
                    if (!empty($setting->mail_coupon_cc)) {
                        $ccList = array_values(array_filter(array_map('trim', explode(',', $setting->mail_coupon_cc))));
                    }
                }
            }

            return [
                'from_address' => $fromAddress,
                'from_name'    => $fromName,
                'cc'           => $ccList,
                'cc_string'    => implode(', ', $ccList),
            ];
        } catch (\Exception $e) {
            return [
                'from_address' => config('mail.from.address', 'no-reply@fiktahadi.com'),
                'from_name'    => config('mail.from.name', 'فيك تحدي / Fiktahadi'),
                'cc'           => [],
                'cc_string'    => '',
            ];
        }
    }
}

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

}

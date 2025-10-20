<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FontFamilySeeder extends Seeder
{
    public function run(): void
    {
        $fonts = [
            ['font_family_name' => 'Arial'],
            ['font_family_name' => 'Roboto'],
            ['font_family_name' => 'Cairo'],
            ['font_family_name' => 'Tajawal'],
            ['font_family_name' => 'Noto Sans Arabic'],
            ['font_family_name' => 'Poppins'],
            ['font_family_name' => 'Amiri'],
            ['font_family_name' => 'Almarai'],
            ['font_family_name' => 'Lato'],
            ['font_family_name' => 'Open Sans'],
        ];

        DB::table('font_families')->insert($fonts);
    }
}

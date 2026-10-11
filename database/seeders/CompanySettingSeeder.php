<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Alma Travel',
                'address' => 'Badriya Towers, Prince Saud Al Faisal District, 2786, Jeddah, Saudi Arabia',
                'brand_primary_color' => '#E6382B',
                'brand_dark_red' => '#C4281C',
                'brand_gold' => '#B89759',
                'brand_surface_light' => '#FFFFFF',
                'brand_surface_dark' => '#1A202C',
                'voucher_theme' => 'classic',
                'tagline' => 'Your Journey, OUR COMMITMENT',
                'logo_path' => null,
            ]
        );
    }
}

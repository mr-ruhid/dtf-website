<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            ['name' => 'Home Hero', 'location' => 'home_hero', 'type' => 'slider'],
            ['name' => 'Home Mid Banner', 'location' => 'home_mid', 'type' => 'banner'],
            ['name' => 'Home Bottom Banner', 'location' => 'home_bottom', 'type' => 'banner'],
            ['name' => 'Category Top', 'location' => 'category_top', 'type' => 'banner'],
            ['name' => 'Product Page Banner', 'location' => 'product_banner', 'type' => 'banner'],
        ];

        foreach ($sliders as $slider) {
            Slider::updateOrCreate(
                ['location' => $slider['location']],
                $slider + ['status' => 1]
            );
        }
    }
}

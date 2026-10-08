<?php

namespace Database\Seeders;

use App\Models\Widget;
use Illuminate\Database\Seeder;

class SignWidgetSeeder extends Seeder
{
    public function run(): void
    {
        Widget::updateOrCreate(
            ['key' => 'sign_hero'],
            [
                'name' => 'Sign Hero',
                'sort_order' => 10,
                'is_active' => 1,
                'settings' => [
                    'eyebrow' => 'Custom Signage Studio',
                    'title' => 'Signs that make your brand unmissable',
                    'title_highlight' => 'unmissable',
                    'description' => 'Premium vinyl, banners and fully custom signs — designed, printed and installed by professionals. From storefronts to stadiums, we bring your vision to life.',
                    'btn1_text' => 'Get a Free Quote',
                    'btn1_url' => '/contact-us',
                    'btn2_text' => 'View Portfolio',
                    'btn2_url' => '#portfolio',
                    'stat1_value' => '15+',
                    'stat1_label' => 'Years Experience',
                    'stat2_value' => '8K+',
                    'stat2_label' => 'Signs Produced',
                    'stat3_value' => '24h',
                    'stat3_label' => 'Rush Turnaround',
                    'card1_icon' => 'fa-solid fa-sign-hanging',
                    'card1_title' => 'Storefront Signs',
                    'card1_meta' => 'From $149',
                    'card2_icon' => 'fa-solid fa-scroll',
                    'card2_title' => 'Vinyl Banners',
                    'card2_meta' => 'From $39',
                    'card3_icon' => 'fa-solid fa-truck-fast',
                    'card3_title' => 'Install Service',
                    'card3_meta' => 'Same Day',
                ],
            ]
        );

        $this->command->info('Sign Hero widget seeded successfully.');
    }
}

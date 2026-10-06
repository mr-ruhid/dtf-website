<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $header = Menu::updateOrCreate(
            ['slug' => 'main-header'],
            [
                'name' => 'Main Header',
                'location' => 'header',
                'status' => true,
            ]
        );

        $headerItems = [
            ['label' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['label' => 'About Us', 'url' => '/about-us', 'sort_order' => 2],
            ['label' => 'Contact Us', 'url' => '/contact-us', 'sort_order' => 3],
            ['label' => 'Blog', 'url' => '/blog', 'sort_order' => 4],
        ];

        foreach ($headerItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $header->id, 'label' => $item['label']],
                array_merge($item, [
                    'menu_id' => $header->id,
                    'parent_id' => null,
                    'target' => '_self',
                    'status' => true,
                ])
            );
        }

        $footer = Menu::updateOrCreate(
            ['slug' => 'main-footer'],
            [
                'name' => 'Main Footer',
                'location' => 'footer',
                'status' => true,
            ]
        );

        $footerItems = [
            ['label' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['label' => 'About Us', 'url' => '/about-us', 'sort_order' => 2],
            ['label' => 'Contact Us', 'url' => '/contact-us', 'sort_order' => 3],
            ['label' => 'FAQ', 'url' => '/faq', 'sort_order' => 4],
            ['label' => 'Terms & Conditions', 'url' => '/terms-conditions', 'sort_order' => 5],
            ['label' => 'Privacy Policy', 'url' => '/privacy-policy', 'sort_order' => 6],
            ['label' => 'Shipping Policy', 'url' => '/shipping-policy', 'sort_order' => 7],
            ['label' => 'Return Policy', 'url' => '/return-policy', 'sort_order' => 8],
        ];

        foreach ($footerItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $footer->id, 'label' => $item['label']],
                array_merge($item, [
                    'menu_id' => $footer->id,
                    'parent_id' => null,
                    'target' => '_self',
                    'status' => true,
                ])
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'key' => 'home',
                'title' => 'Home',
                'slug' => 'home',
                'excerpt' => 'Welcome to RJ SHOP - Your DTF printing partner',
                'content' => '<p>Welcome to RJ SHOP - Your one-stop shop for DTF transfers, UV stickers, blank apparel and more.</p>',
                'show_in_footer' => false,
                'show_in_header' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'about',
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Learn more about RJ SHOP',
                'content' => '<h2>About RJ SHOP</h2><p>We are a leading provider of DTF transfers, UV stickers, blank apparel, signs and special films. Our mission is to deliver high-quality products with fast turnaround times.</p>',
                'show_in_footer' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'contact',
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'excerpt' => 'Get in touch with our team',
                'content' => '<h2>Get in Touch</h2><p>We would love to hear from you. Reach out with any questions or inquiries.</p>',
                'show_in_footer' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'blog',
                'title' => 'Blog',
                'slug' => 'blog',
                'excerpt' => 'News, tips and updates',
                'content' => '<p>Read our latest articles, tips and industry news.</p>',
                'show_in_footer' => false,
                'show_in_header' => true,
                'sort_order' => 4,
            ],
            [
                'key' => 'faq',
                'title' => 'FAQ',
                'slug' => 'faq',
                'excerpt' => 'Frequently asked questions',
                'content' => '<p>Find answers to common questions about our products and services.</p>',
                'show_in_footer' => true,
                'sort_order' => 5,
            ],
            [
                'key' => 'terms',
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'excerpt' => 'Our terms of service',
                'content' => '<h2>Terms & Conditions</h2><p>Please read these terms carefully before using our services.</p>',
                'show_in_footer' => true,
                'sort_order' => 6,
            ],
            [
                'key' => 'privacy',
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'How we handle your data',
                'content' => '<h2>Privacy Policy</h2><p>We respect your privacy and are committed to protecting your personal data.</p>',
                'show_in_footer' => true,
                'sort_order' => 7,
            ],
            [
                'key' => 'shipping',
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'excerpt' => 'Shipping information',
                'content' => '<h2>Shipping Policy</h2><p>We ship orders within the United States. Delivery times vary by location.</p>',
                'show_in_footer' => true,
                'sort_order' => 8,
            ],
            [
                'key' => 'return',
                'title' => 'Return Policy',
                'slug' => 'return-policy',
                'excerpt' => 'Returns and refunds',
                'content' => '<h2>Return Policy</h2><p>We accept returns within 30 days of purchase for unopened items.</p>',
                'show_in_footer' => true,
                'sort_order' => 9,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['key' => $page['key']],
                array_merge($page, [
                    'type' => 'static',
                    'is_locked' => true,
                    'status' => true,
                ])
            );
        }
    }
}

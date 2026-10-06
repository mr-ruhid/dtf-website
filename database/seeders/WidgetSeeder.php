<?php

namespace Database\Seeders;

use App\Models\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    public function run(): void
    {
        $widgets = [
            [
                'key' => 'hero',
                'name' => 'Hero Banner',
                'sort_order' => 1,
                'settings' => [
                    'location' => 'home_hero',
                ],
            ],
            [
                'key' => 'steps',
                'name' => 'How It Works',
                'sort_order' => 2,
                'settings' => [
                    'title' => 'Cut. Place. Press. Peel.',
                    'subtitle' => 'We print the film. You press it onto the shirt. That is the whole product.',
                    'link_text' => 'See the guide',
                    'link_url' => '/how-to-press',
                    'items' => [
                        ['number' => '01', 'title' => 'Cut', 'description' => 'Your sheet arrives uncut. Snip each design off. Keep extras in a drawer.', 'image' => ''],
                        ['number' => '02', 'title' => 'Place', 'description' => 'Park it on the garment, print facing down. Cotton, poly, or a blend.', 'image' => ''],
                        ['number' => '03', 'title' => 'Press', 'description' => '310°F / 155°C. Medium pressure. 12–15 seconds. Heat press or EasyPress.', 'image' => ''],
                        ['number' => '04', 'title' => 'Peel', 'description' => 'Wait 5 seconds. Peel warm. Enjoy your custom print.', 'image' => ''],
                    ],
                    'button_text' => 'Build a DTF Gangsheet',
                    'button_url' => '/products',
                ],
            ],
            [
                'key' => 'features',
                'name' => 'Features',
                'sort_order' => 3,
                'settings' => [
                    'title' => 'Premium DTF Transfer Performance You Can Trust',
                    'subtitle' => 'Our premium DTF transfers are engineered to deliver consistent, professional-grade results across a wide range of fabrics.',
                    'items' => [
                        ['icon' => 'fa-check', 'title' => 'Strong Adhesion', 'description' => 'Transfers bond firmly to fabric — no peeling, lifting, or edge curling.'],
                        ['icon' => 'fa-check', 'title' => 'Soft Hand Feel', 'description' => 'Enjoy a smooth, comfortable finish without a thick or stiff texture.'],
                        ['icon' => 'fa-check', 'title' => 'Crack-Resistant Flexibility', 'description' => 'Built to stretch with garments while staying clean and intact.'],
                        ['icon' => 'fa-check', 'title' => 'Works on Multiple Fabrics', 'description' => 'Perfect results on cotton, polyester, blends, and more.'],
                    ],
                ],
            ],
            [
                'key' => 'categories',
                'name' => 'Shop by Category',
                'sort_order' => 4,
                'settings' => [
                    'title' => 'Shop by Category',
                    'subtitle' => 'Browse our full range of products',
                    'limit' => 8,
                ],
            ],
            [
                'key' => 'featured_products',
                'name' => 'Featured Products',
                'sort_order' => 5,
                'settings' => [
                    'title' => 'Featured Products',
                    'subtitle' => 'Handpicked by our team',
                    'limit' => 8,
                ],
            ],
            [
                'key' => 'testimonials',
                'name' => 'Testimonials',
                'sort_order' => 6,
                'settings' => [
                    'title' => 'What Our Customers Say',
                    'subtitle' => 'Real reviews from real customers',
                    'items' => [
                        ['name' => 'John Smith', 'role' => 'Small Business Owner', 'text' => 'Fastest turnaround I have ever experienced. Quality is outstanding.', 'rating' => 5, 'avatar' => ''],
                        ['name' => 'Sarah Johnson', 'role' => 'Print Shop Owner', 'text' => 'The colors are vibrant and the transfers hold up wash after wash.', 'rating' => 5, 'avatar' => ''],
                        ['name' => 'Mike Davis', 'role' => 'Custom Apparel Brand', 'text' => 'My go-to supplier for all DTF needs. Never disappointed.', 'rating' => 5, 'avatar' => ''],
                    ],
                ],
            ],
            [
                'key' => 'stats',
                'name' => 'Statistics',
                'sort_order' => 7,
                'settings' => [
                    'items' => [
                        ['icon' => 'fa-box', 'value' => '50K+', 'label' => 'Orders Delivered'],
                        ['icon' => 'fa-users', 'value' => '10K+', 'label' => 'Happy Customers'],
                        ['icon' => 'fa-star', 'value' => '4.9', 'label' => 'Average Rating'],
                        ['icon' => 'fa-clock', 'value' => '24h', 'label' => 'Fast Turnaround'],
                    ],
                ],
            ],
            [
                'key' => 'info',
                'name' => 'Custom Info Section',
                'sort_order' => 8,
                'settings' => [
                    'title' => 'About Our Process',
                    'content' => '<p>We use only the highest quality materials and state-of-the-art printing technology. Every order is inspected by hand before it ships.</p>',
                    'button_text' => 'Learn More',
                    'button_url' => '/about-us',
                ],
            ],
            [
                'key' => 'slider_mid',
                'name' => 'Mid Banner',
                'sort_order' => 9,
                'settings' => [
                    'location' => 'home_mid',
                ],
            ],
            [
                'key' => 'blog_preview',
                'name' => 'Latest Blog Posts',
                'sort_order' => 10,
                'settings' => [
                    'title' => 'Latest from the Blog',
                    'subtitle' => 'Tips, guides, and industry news',
                    'limit' => 3,
                ],
            ],
            [
                'key' => 'faq_preview',
                'name' => 'FAQ',
                'sort_order' => 11,
                'settings' => [
                    'title' => 'Frequently Asked Questions',
                    'subtitle' => 'Quick answers to common questions',
                    'limit' => 6,
                    'button_text' => 'View All FAQs',
                    'button_url' => '/faq',
                ],
            ],
            [
                'key' => 'newsletter',
                'name' => 'Newsletter Subscribe',
                'sort_order' => 12,
                'settings' => [
                    'title' => 'Join Our Newsletter',
                    'subtitle' => 'Get the latest updates, tips, and exclusive offers delivered to your inbox.',
                    'placeholder' => 'Enter your email',
                    'button_text' => 'Subscribe',
                ],
            ],
            [
                'key' => 'cta',
                'name' => 'Call to Action',
                'sort_order' => 13,
                'settings' => [
                    'title' => 'Ready to Start Your Project?',
                    'subtitle' => 'Upload your design, pick your product, and we will handle the rest.',
                    'button1_text' => 'Start Your Project',
                    'button1_url' => '/contact-us',
                    'button2_text' => 'Browse Products',
                    'button2_url' => '/products',
                ],
            ],
            [
                'key' => 'live_chat',
                'name' => 'Live Chat',
                'sort_order' => 14,
                'settings' => [
                    'welcome_message' => 'Hi! How can we help?',
                    'agent_name' => 'Support Team',
                    'agent_avatar' => '',
                    'working_hours' => 'Mon-Fri, 9am-6pm',
                    'offline_message' => 'We are offline. Leave a message and we will get back to you.',
                ],
            ],
        ];

        foreach ($widgets as $widget) {
            Widget::updateOrCreate(
                ['key' => $widget['key']],
                [
                    'name' => $widget['name'],
                    'settings' => $widget['settings'],
                    'sort_order' => $widget['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

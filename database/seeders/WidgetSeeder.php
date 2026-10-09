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
                    'eyebrow' => 'How it works',
                    'title' => 'Cut. Place. Press. Peel.',
                    'subtitle' => 'We print the film. You press it onto the shirt. That is the whole product.',
                    'button_text' => 'Build a DTF Gangsheet',
                    'button_url' => '/products',
                    'items' => [
                        ['number' => '01', 'title' => 'Cut', 'description' => 'Your sheet arrives uncut. Snip each design off. Keep extras in a drawer.', 'link_text' => 'See the guide', 'link_url' => '/how-to-press', 'image' => ''],
                        ['number' => '02', 'title' => 'Place', 'description' => 'Park it on the garment, print facing down. Cotton, poly, or a blend.', 'link_text' => 'See the guide', 'link_url' => '/how-to-press', 'image' => ''],
                        ['number' => '03', 'title' => 'Press', 'description' => '310°F / 155°C. Medium pressure. 12-15 seconds. Heat press or EasyPress.', 'link_text' => 'See the guide', 'link_url' => '/how-to-press', 'image' => ''],
                        ['number' => '04', 'title' => 'Peel', 'description' => 'Wait 5 seconds. Peel warm. Enjoy your custom print.', 'link_text' => 'See the guide', 'link_url' => '/how-to-press', 'image' => ''],
                    ],
                ],
            ],
            [
                'key' => 'build_or_upload',
                'name' => 'Build or Upload',
                'sort_order' => 3,
                'settings' => [
                    'eyebrow' => 'Got a file?',
                    'title' => 'Two ways to start. One result.',
                    'subtitle' => 'Build it or upload it. Same film, same day. That\'s the whole menu.',
                    'build_card' => [
                        'badge' => 'No file',
                        'title' => 'Build it',
                        'description' => 'Design your gang sheet in our online builder. Drag, drop, done.',
                        'button_text' => 'Open the builder',
                        'button_url' => '/design',
                        'image' => '',
                    ],
                    'upload_card' => [
                        'badge' => 'Ready',
                        'title' => 'Upload it',
                        'description' => 'Already have a print-ready file? Send it and we\'ll handle the rest.',
                        'button_text' => 'Send the file',
                        'button_url' => '/contact-us',
                        'image' => '',
                    ],
                    'info_card' => [
                        'badge' => 'That\'s all',
                        'number' => '3',
                        'title' => 'No third step',
                        'description' => 'Build it or upload it. Same film, same day. That\'s the whole menu.',
                    ],
                ],
            ],
            [
                'key' => 'features',
                'name' => 'Features',
                'sort_order' => 4,
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
                'sort_order' => 5,
                'settings' => [
                    'title' => 'Shop by Category',
                    'subtitle' => 'Browse our full range of products',
                    'limit' => 8,
                ],
            ],
            [
                'key' => 'featured_products',
                'name' => 'Featured Products',
                'sort_order' => 6,
                'settings' => [
                    'title' => 'Featured Products',
                    'subtitle' => 'Handpicked by our team',
                    'limit' => 8,
                ],
            ],
            [
                'key' => 'testimonials',
                'name' => 'Testimonials',
                'sort_order' => 7,
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
                'sort_order' => 8,
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
                'sort_order' => 9,
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
                'sort_order' => 10,
                'settings' => [
                    'location' => 'home_mid',
                ],
            ],
            [
                'key' => 'blog_preview',
                'name' => 'Latest Blog Posts',
                'sort_order' => 11,
                'settings' => [
                    'title' => 'Latest from the Blog',
                    'subtitle' => 'Tips, guides, and industry news',
                    'limit' => 3,
                ],
            ],
            [
                'key' => 'faq_preview',
                'name' => 'FAQ',
                'sort_order' => 12,
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
                'sort_order' => 13,
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
                'sort_order' => 14,
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
                'sort_order' => 15,
                'settings' => [
                    'welcome_message' => 'Hi! How can we help?',
                    'agent_name' => 'Support Team',
                    'agent_avatar' => '',
                    'working_hours' => 'Mon-Fri, 9am-6pm',
                    'offline_message' => 'We are offline. Leave a message and we will get back to you.',
                ],
            ],
            [
                'key' => 'sign_hero',
                'name' => 'Sign Hero',
                'sort_order' => 16,
                'settings' => [
                    'eyebrow' => 'Large-Format Print',
                    'title' => "Custom Signs, Vinyl\n& Banners",
                    'subtitle' => "Send the artwork. We print it.\nYou collect.",
                    'main_image' => '',
                    'image_2' => '',
                    'image_3' => '',
                    'step1_text' => 'Send the file',
                    'step2_text' => 'We print it',
                    'step3_text' => 'You collect',
                ],
            ],
            [
                'key' => 'special_films_story',
                'name' => 'Special Films Story',
                'sort_order' => 17,
                'settings' => [
                    'stats' => [
                        ['value' => '310°F', 'sub' => '155°C', 'label' => 'Temperature'],
                        ['value' => 'Medium', 'sub' => '', 'label' => 'Pressure'],
                        ['value' => '12–15 sec', 'sub' => '', 'label' => 'Press time'],
                        ['value' => '~5 sec', 'sub' => '', 'label' => 'Then peel'],
                        ['value' => '5–10 sec', 'sub' => '', 'label' => 'Second press'],
                    ],
                    'story_eyebrow' => 'THE STORY',
                    'story_title' => 'Want it. Press it. Send the file.',
                    'steps' => [
                        [
                            'title' => 'You wanted sparkle',
                            'description' => 'Glitter DTF is a standard DTF transfer with a durable glitter layer built into the print. It does not flake like loose glitter vinyl. Chunky sparkle stays in the film - no glitter fallout on the press. Dance, cheer, birthday, and statement apparel.',
                        ],
                        [
                            'title' => 'You press it the way you already know',
                            'description' => 'Same settings as standard DTF: 310°F / 155°C, medium pressure, 12–15 seconds. Peel after about 5 seconds, then a second press of 5–10 seconds. Works on cotton, blends, and the blanks you already buy from us.',
                        ],
                        [
                            'title' => 'Then you send the art',
                            'description' => 'Upload your art on the Glitter DTF Gang Sheet and we print it the same day. Glitter DTF is the only specialty film we sell right now. No minimums.',
                        ],
                    ],
                    'cta_text' => 'Upload your Glitter DTF design',
                    'cta_url' => '/design/glitter-dtf-transfers',
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

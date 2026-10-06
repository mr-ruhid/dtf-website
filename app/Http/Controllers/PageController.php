<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Setting;

class PageController extends Controller
{
    public function home()
    {
        $page = Page::findByKey('home');

        abort_if(!$page, 404);

        $widgets = \App\Models\Widget::activeWidgets();

        return view('theme.rjshop-theme.staticpages.home', compact('page', 'widgets'));
    }

    public function about()
    {
        $page = Page::findByKey('about');

        abort_if(!$page, 404);

        return view('theme.rjshop-theme.staticpages.about', compact('page'));
    }

    public function contact()
    {
        $page = Page::findByKey('contact');

        abort_if(!$page, 404);

        $settings = Setting::getMany([
            'site_name',
            'site_email',
            'site_phone',
            'site_address',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'linkedin_url',
            'tiktok_url',
        ]);

        return view('theme.rjshop-theme.staticpages.contact', compact('page', 'settings'));
    }

    public function faq()
    {
        $page = Page::findByKey('faq');

        abort_if(!$page, 404);

        $faqs = Faq::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('theme.rjshop-theme.staticpages.faq', compact('page', 'faqs'));
    }

    public function design()
    {
        return view('theme.rjshop-theme.staticpages.design');
    }
}

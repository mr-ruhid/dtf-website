<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;

class PageController extends Controller
{
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
}

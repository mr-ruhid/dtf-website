<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function about()
    {
        $page = Page::findByKey('about');

        abort_if(!$page, 404);

        return view('theme.rjshop-theme.staticpages.about', compact('page'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::where('status', 1)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $imageCount = $items->where('type', 'image')->count();
        $videoCount = $items->where('type', 'video')->count();

        return view('theme.rjshop-theme.staticpages.gallery', compact('items', 'imageCount', 'videoCount'));
    }
}
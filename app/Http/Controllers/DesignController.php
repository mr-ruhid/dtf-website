<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PrintZone;

class DesignController extends Controller
{
    public function index(?string $slug = null)
    {
        $zones = PrintZone::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        $product = null;

        if ($slug) {
            $product = Product::with(['printZones', 'images'])
                ->where('slug', $slug)
                ->where('status', 1)
                ->first();

            if ($product && $product->printZones->count()) {
                $zones = $product->printZones
                    ->where('status', 1)
                    ->sortBy('sort_order')
                    ->values();
            }
        }

        $zonesPayload = $zones->map(function (PrintZone $zone) {
            $w = (float) ($zone->max_width_inch ?: 12);
            $h = (float) ($zone->max_height_inch ?: 12);

            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'slug' => $zone->slug,
                'width_inch' => $w,
                'height_inch' => $h,
                'label' => $w . ' × ' . $h . ' in',
                'price_addon' => (float) $zone->price_addon,
            ];
        })->values();

        $productPayload = null;

        if ($product) {
            $productPayload = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'base_price' => (float) ($product->sale_price ?: $product->base_price),
                'image' => $product->images->first()?->url,
                'print_type' => $product->print_type,
            ];
        }

        return view('theme.rjshop-theme.staticpages.design', [
            'zones' => $zonesPayload,
            'product' => $productPayload,
        ]);
    }
}

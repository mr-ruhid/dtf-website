<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([
            'images',
            'prices',
            'options.values',
            'attributeValues.attribute',
            'attributeValues.attributeValue',
            'attributeValues.productImage',
            'printZones',
            'category',
            'model',
        ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $relatedProducts = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->category_id) {
                    $q->where('category_id', $product->category_id);
                } elseif ($product->model_id) {
                    $q->where('model_id', $product->model_id);
                } else {
                    $q->whereNull('id');
                }
            })
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $faqs = Faq::where('status', 1)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $view = $product->print_type === 'apparel'
            ? 'theme.rjshop-theme.staticpages.product-apparel'
            : 'theme.rjshop-theme.staticpages.product';

        return view($view, compact('product', 'relatedProducts', 'faqs'));
    }
}

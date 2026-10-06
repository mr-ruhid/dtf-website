<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductModel;

class ModelController extends Controller
{
    public function show(string $slug)
    {
        $model = ProductModel::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        if ($model->isSingle && $model->single_product_id) {
            return $this->showSingle($model);
        }

        if ($model->isCustom && $model->custom_view) {
            $view = 'theme.rjshop-theme.models.' . $model->custom_view;

            if (view()->exists($view)) {
                return view($view, compact('model'));
            }
        }

        return $this->showGrid($model);
    }

    protected function showSingle(ProductModel $model)
    {
        $product = Product::with([
            'images',
            'prices',
            'options.values',
            'attributeValues.attribute',
            'attributeValues.attributeValue',
            'printZones',
            'category',
            'model',
        ])
            ->where('id', $model->single_product_id)
            ->where('status', 1)
            ->firstOrFail();

        $relatedProducts = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($model) {
                $q->where('model_id', $model->id)
                  ->orWhereNull('model_id');
            })
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $faqs = \App\Models\Faq::where('status', 1)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('theme.rjshop-theme.models.single', compact('model', 'product', 'relatedProducts', 'faqs'));
    }

    protected function showGrid(ProductModel $model)
    {
        $categories = $model->rootCategories()->with('children')->get();
        $products = Product::where('model_id', $model->id)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->paginate(12);

        return view('theme.rjshop-theme.models.grid', compact('model', 'categories', 'products'));
    }
}

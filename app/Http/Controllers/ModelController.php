<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function show(Request $request, string $slug, ?string $category = null)
    {
        $model = ProductModel::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        if ($model->isSingle && $model->single_product_id) {
            return $this->showSingle($model);
        }

        if ($model->isCustom && $model->custom_view) {
            $view = 'theme.rjshop-theme.rjshop.model.' . $model->custom_view;

            if (view()->exists($view)) {
                return view($view, compact('model'));
            }
        }

        return $this->showGrid($model, $request, $category);
    }

    protected function showSingle(ProductModel $model)
    {
        $product = Product::with([
            'images',
            'prices',
            'options.values',
            'options.measurements',
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

        return view('theme.rjshop-theme.rjshop.model.single', compact('model', 'product', 'relatedProducts', 'faqs'));
    }

    protected function showGrid(ProductModel $model, Request $request, ?string $categorySlug = null)
    {
        $categories = $model->rootCategories()
            ->where('status', 1)
            ->with(['children' => function ($q) {
                $q->where('status', 1)->orderBy('sort_order');
            }])
            ->get();

        $activeCategory = null;

        if ($categorySlug) {
            $activeCategory = Category::where('model_id', $model->id)
                ->where('slug', $categorySlug)
                ->where('status', 1)
                ->firstOrFail();
        }

        $attributes = Attribute::where('status', 1)
            ->with(['activeValues'])
            ->orderBy('sort_order')
            ->get();

        $query = Product::with([
                'images',
                'prices',
                'variants',
                'attributeValues.attribute',
                'attributeValues.attributeValue',
            ])
            ->where('model_id', $model->id)
            ->where('status', 1);

        if ($activeCategory) {
            $catIds = [$activeCategory->id];
            foreach ($activeCategory->children as $child) {
                $catIds[] = $child->id;
            }
            $query->whereIn('category_id', $catIds);
        }

        $attrFilters = $request->input('attr', []);

        if (is_array($attrFilters)) {
            foreach ($attrFilters as $attrId => $values) {
                $values = array_values(array_filter((array) $values, fn($v) => $v !== '' && $v !== null));

                if (!count($values)) {
                    continue;
                }

                $query->whereHas('attributeValues', function ($q) use ($attrId, $values) {
                    $q->where('attribute_id', (int) $attrId)
                      ->whereIn('attribute_value_id', $values);
                });
            }
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', (float) $request->input('max_price'));
        }

        $sort = $request->input('sort', 'new');

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $sort = 'new';
                $query->orderByDesc('id');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('theme.rjshop-theme.models.grid', compact(
            'model',
            'categories',
            'products',
            'activeCategory',
            'attributes',
            'sort'
        ));
    }
}

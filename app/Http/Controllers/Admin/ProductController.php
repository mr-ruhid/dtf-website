<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\PrintZone;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductModel;
use App\Models\ProductOption;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['model', 'category', 'images']);

        if ($request->filled('model_id')) {
            $query->where('model_id', $request->input('model_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order')->latest()->paginate(15)->withQueryString();
        $models = ProductModel::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.product.index', compact('products', 'models', 'categories'));
    }

    public function create()
    {
        $models = ProductModel::orderBy('name')->get();
        $categories = Category::with('model')->orderBy('name')->get();
        $attributes = Attribute::with('activeValues')->where('status', 1)->orderBy('sort_order')->get();
        $printZones = PrintZone::where('status', 1)->orderBy('sort_order')->get();

        return view('admin.product.create', compact('models', 'categories', 'attributes', 'printZones'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['status'] = $request->boolean('status', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['has_variants'] = $request->boolean('has_variants');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        DB::beginTransaction();

        try {
            $product = Product::create($data);

            $this->saveImages($product, $request);
            $this->savePrices($product, $request);
            $this->saveAttributeValues($product, $request);
            $this->savePrintZones($product, $request);

            DB::commit();

            return redirect()->route('admin.products.edit', $product)
                ->with('status', 'Product created. You can now add options and variants.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load([
            'images',
            'prices',
            'attributeValues.attribute',
            'attributeValues.attributeValue',
            'printZones',
            'options.values',
            'variants',
        ]);

        $models = ProductModel::orderBy('name')->get();
        $categories = Category::with('model')->orderBy('name')->get();
        $attributes = Attribute::with('activeValues')->where('status', 1)->orderBy('sort_order')->get();
        $printZones = PrintZone::where('status', 1)->orderBy('sort_order')->get();

        return view('admin.product.edit', compact('product', 'models', 'categories', 'attributes', 'printZones'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product->id);

        $data['status'] = $request->boolean('status');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['has_variants'] = $request->boolean('has_variants');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        DB::beginTransaction();

        try {
            $product->update($data);

            $this->saveImages($product, $request);
            $this->savePrices($product, $request);
            $this->saveAttributeValues($product, $request);
            $this->savePrintZones($product, $request);

            DB::commit();

            return back()->with('status', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if ($image->image && !str_starts_with($image->image, 'http')) {
                Storage::disk('public')->delete($image->image);
            }
        }

        foreach ($product->attributeValues as $pav) {
            if ($pav->image && !str_starts_with($pav->image, 'http')) {
                Storage::disk('public')->delete($pav->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);
        return back()->with('status', 'Product status updated.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return back()->with('status', 'Featured status updated.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        if ($image->image && !str_starts_with($image->image, 'http')) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('status', 'Image deleted.');
    }

    protected function saveImages(Product $product, Request $request): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $sortStart = $product->images()->max('sort_order') ?? 0;

        foreach ($request->file('images') as $i => $file) {
            $path = $file->store('products', 'public');
            $product->images()->create([
                'image' => $path,
                'sort_order' => $sortStart + $i + 1,
            ]);
        }
    }

    protected function savePrices(Product $product, Request $request): void
    {
        $product->prices()->delete();

        $tiers = $request->input('tiers', []);

        foreach ($tiers as $tier) {
            if (empty($tier['min_qty']) || !isset($tier['price']) || $tier['price'] === '') {
                continue;
            }

            $product->prices()->create([
                'min_qty' => (int) $tier['min_qty'],
                'max_qty' => !empty($tier['max_qty']) ? (int) $tier['max_qty'] : null,
                'price' => (float) $tier['price'],
            ]);
        }
    }

    protected function saveAttributeValues(Product $product, Request $request): void
    {
        $product->attributeValues()->delete();

        $selected = $request->input('attribute_values', []);

        foreach ($selected as $attributeId => $values) {
            foreach ($values as $valueData) {
                if (empty($valueData['value_id'])) {
                    continue;
                }

                $product->attributeValues()->create([
                    'attribute_id' => $attributeId,
                    'attribute_value_id' => $valueData['value_id'],
                    'price_override' => isset($valueData['price_override']) && $valueData['price_override'] !== ''
                        ? (float) $valueData['price_override']
                        : null,
                    'status' => true,
                ]);
            }
        }

        if ($request->hasFile('attribute_images')) {
            foreach ($request->file('attribute_images') as $attributeId => $files) {
                foreach ($files as $valueId => $file) {
                    if (!$file) {
                        continue;
                    }

                    $pav = $product->attributeValues()
                        ->where('attribute_id', $attributeId)
                        ->where('attribute_value_id', $valueId)
                        ->first();

                    if ($pav) {
                        if ($pav->image && !str_starts_with($pav->image, 'http')) {
                            Storage::disk('public')->delete($pav->image);
                        }
                        $path = $file->store('products/attributes', 'public');
                        $pav->update(['image' => $path]);
                    }
                }
            }
        }
    }

    protected function savePrintZones(Product $product, Request $request): void
    {
        $zones = $request->input('print_zones', []);
        $product->printZones()->sync($zones);
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'model_id' => ['nullable', 'exists:models,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:products,slug' . ($ignoreId ? ',' . $ignoreId : '')],
            'sku' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'print_type' => ['required', 'in:apparel,custom_size,fixed_area,none'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'has_variants' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:300'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'tiers' => ['nullable', 'array'],
            'attribute_values' => ['nullable', 'array'],
            'print_zones' => ['nullable', 'array'],
        ]);
    }
}

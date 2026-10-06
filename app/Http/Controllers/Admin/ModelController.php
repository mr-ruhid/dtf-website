<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    public function index()
    {
        $models = ProductModel::orderBy('sort_order')->orderBy('name')->paginate(15);
        return view('admin.model.index', compact('models'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.model.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['status'] = $request->boolean('status');
        $data['show_in_header'] = $request->boolean('show_in_header');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['display_type'] = $data['display_type'] ?? 'grid';

        if ($data['display_type'] !== 'single') {
            $data['single_product_id'] = null;
        }
        if ($data['display_type'] !== 'custom') {
            $data['custom_view'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('models', 'public');
        }

        ProductModel::create($data);

        return redirect()->route('admin.models.index')->with('status', 'Model created successfully.');
    }

    public function edit(ProductModel $model)
    {
        $products = Product::orderBy('name')->get();
        return view('admin.model.edit', compact('model', 'products'));
    }

    public function update(Request $request, ProductModel $model)
    {
        $data = $this->validateData($request, $model->id);

        $data['status'] = $request->boolean('status');
        $data['show_in_header'] = $request->boolean('show_in_header');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['display_type'] = $data['display_type'] ?? 'grid';

        if ($data['display_type'] !== 'single') {
            $data['single_product_id'] = null;
        }
        if ($data['display_type'] !== 'custom') {
            $data['custom_view'] = null;
        }

        if ($request->hasFile('image')) {
            if ($model->image && !str_starts_with($model->image, 'http')) {
                Storage::disk('public')->delete($model->image);
            }
            $data['image'] = $request->file('image')->store('models', 'public');
        }

        $model->update($data);

        return redirect()->route('admin.models.index')->with('status', 'Model updated successfully.');
    }

    public function destroy(ProductModel $model)
    {
        if ($model->image && !str_starts_with($model->image, 'http')) {
            Storage::disk('public')->delete($model->image);
        }

        $model->delete();

        return redirect()->route('admin.models.index')->with('status', 'Model deleted successfully.');
    }

    public function toggleStatus(ProductModel $model)
    {
        $model->update(['status' => !$model->status]);
        return back()->with('status', 'Model status updated.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:models,slug' . ($ignoreId ? ',' . $ignoreId : '')],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'show_in_header' => ['nullable', 'boolean'],
            'display_type' => ['nullable', 'in:grid,single,custom'],
            'single_product_id' => ['nullable', 'exists:products,id'],
            'custom_view' => ['nullable', 'string', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);
    }
}

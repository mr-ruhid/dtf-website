<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $models = ProductModel::orderBy('sort_order')->orderBy('name')->get();
        $categories = Category::with(['model', 'children'])
            ->roots()
            ->withCount('children')
            ->orderBy('sort_order')
            ->get();

        return view('admin.category.index', compact('models', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['parent_id'] = $data['parent_id'] ?: null;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return back()->with('status', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateData($request, $category->id);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['parent_id'] = $data['parent_id'] ?: null;

        if ($data['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Category cannot be its own parent.']);
        }

        if ($request->hasFile('image')) {
            if ($category->image && !str_starts_with($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return back()->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        foreach ($category->children as $child) {
            if ($child->image && !str_starts_with($child->image, 'http')) {
                Storage::disk('public')->delete($child->image);
            }
            $child->delete();
        }

        if ($category->image && !str_starts_with($category->image, 'http')) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('status', 'Category deleted successfully.');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['status' => !$category->status]);
        return back()->with('status', 'Category status updated.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'model_id' => ['required', 'exists:models,id'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:categories,slug' . ($ignoreId ? ',' . $ignoreId : '')],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);
    }
}

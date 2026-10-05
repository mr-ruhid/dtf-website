<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.attribute.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:attributes,slug'],
            'type' => ['required', 'in:select,color,text'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Attribute::create($data);

        return back()->with('status', 'Attribute created successfully.');
    }

    public function update(Request $request, Attribute $attribute)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:attributes,slug,' . $attribute->id],
            'type' => ['required', 'in:select,color,text'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $attribute->update($data);

        return back()->with('status', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        if ($attribute->is_locked) {
            return back()->withErrors(['error' => 'This attribute is locked and cannot be deleted.']);
        }

        $attribute->delete();

        return back()->with('status', 'Attribute deleted successfully.');
    }

    public function toggleStatus(Attribute $attribute)
    {
        $attribute->update(['status' => !$attribute->status]);
        return back()->with('status', 'Attribute status updated.');
    }

    public function storeValue(Request $request, Attribute $attribute)
    {
        $data = $request->validate([
            'value' => ['required', 'string', 'max:100'],
            'color_code' => ['nullable', 'string', 'max:20'],
            'price_adjustment' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? ($attribute->values()->max('sort_order') + 1);
        $data['price_adjustment'] = $data['price_adjustment'] ?? 0;

        if ($attribute->type === 'color' && empty($data['color_code'])) {
            return back()->withErrors(['color_code' => 'Color code is required for color attributes.']);
        }

        $attribute->values()->create($data);

        return back()->with('status', 'Value added successfully.');
    }

    public function updateValue(Request $request, Attribute $attribute, AttributeValue $value)
    {
        if ($value->attribute_id !== $attribute->id) {
            abort(404);
        }

        $data = $request->validate([
            'value' => ['required', 'string', 'max:100'],
            'color_code' => ['nullable', 'string', 'max:20'],
            'price_adjustment' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['price_adjustment'] = $data['price_adjustment'] ?? 0;

        $value->update($data);

        return back()->with('status', 'Value updated successfully.');
    }

    public function destroyValue(Attribute $attribute, AttributeValue $value)
    {
        if ($value->attribute_id !== $attribute->id) {
            abort(404);
        }

        $value->delete();

        return back()->with('status', 'Value deleted successfully.');
    }

    public function toggleValueStatus(Attribute $attribute, AttributeValue $value)
    {
        if ($value->attribute_id !== $attribute->id) {
            abort(404);
        }

        $value->update(['status' => !$value->status]);
        return back()->with('status', 'Value status updated.');
    }
}

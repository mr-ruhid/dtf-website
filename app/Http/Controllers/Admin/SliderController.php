<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\SliderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::withCount('items')->latest()->get();
        return view('admin.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:100', 'unique:sliders,location'],
            'type' => ['required', 'in:slider,banner'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('status', 'Slider created successfully.');
    }

    public function edit(Slider $slider)
    {
        $slider->load('items');
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:100', 'unique:sliders,location,' . $slider->id],
            'type' => ['required', 'in:slider,banner'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');

        $slider->update($data);

        return back()->with('status', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        foreach ($slider->items as $item) {
            if ($item->image && !str_starts_with($item->image, 'http')) {
                Storage::disk('public')->delete($item->image);
            }
        }
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('status', 'Slider deleted successfully.');
    }

    public function storeItem(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'text_position' => ['required', 'in:left,center,right'],
            'text_color' => ['required', 'in:light,dark'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['image'] = $request->file('image')->store('sliders', 'public');
        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $slider->items()->create($data);

        return back()->with('status', 'Item added successfully.');
    }

    public function updateItem(Request $request, Slider $slider, SliderItem $item)
    {
        if ($item->slider_id !== $slider->id) {
            abort(404);
        }

        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'text_position' => ['required', 'in:left,center,right'],
            'text_color' => ['required', 'in:light,dark'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($item->image && !str_starts_with($item->image, 'http')) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $item->update($data);

        return back()->with('status', 'Item updated successfully.');
    }

    public function destroyItem(Slider $slider, SliderItem $item)
    {
        if ($item->slider_id !== $slider->id) {
            abort(404);
        }

        if ($item->image && !str_starts_with($item->image, 'http')) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return back()->with('status', 'Item deleted successfully.');
    }
}

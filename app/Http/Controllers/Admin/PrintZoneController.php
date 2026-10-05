<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrintZoneController extends Controller
{
    public function index()
    {
        $zones = PrintZone::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.print-zone.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:print_zones,slug'],
            'max_width_inch' => ['nullable', 'numeric', 'min:0'],
            'max_height_inch' => ['nullable', 'numeric', 'min:0'],
            'price_addon' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['price_addon'] = $data['price_addon'] ?? 0;

        PrintZone::create($data);

        return back()->with('status', 'Print zone created successfully.');
    }

    public function update(Request $request, PrintZone $zone)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:print_zones,slug,' . $zone->id],
            'max_width_inch' => ['nullable', 'numeric', 'min:0'],
            'max_height_inch' => ['nullable', 'numeric', 'min:0'],
            'price_addon' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['price_addon'] = $data['price_addon'] ?? 0;

        $zone->update($data);

        return back()->with('status', 'Print zone updated successfully.');
    }

    public function destroy(PrintZone $zone)
    {
        $zone->delete();
        return back()->with('status', 'Print zone deleted successfully.');
    }

    public function toggleStatus(PrintZone $zone)
    {
        $zone->update(['status' => !$zone->status]);
        return back()->with('status', 'Print zone status updated.');
    }
}

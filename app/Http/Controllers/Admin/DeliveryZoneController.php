<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\DeliveryZoneRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        $zones = DeliveryZone::with('regions')->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.delivery-zone.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:delivery_zones,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'regions' => ['nullable', 'array'],
            'regions.*.type' => ['required_with:regions', 'in:state,city,zip'],
            'regions.*.value' => ['required_with:regions', 'string', 'max:100'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['color'] = $data['color'] ?? '#6366f1';

        $regions = $data['regions'] ?? [];
        unset($data['regions']);

        $zone = DeliveryZone::create($data);

        $this->saveRegions($zone, $regions);

        return back()->with('status', 'Delivery zone created successfully.');
    }

    public function update(Request $request, DeliveryZone $zone)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:delivery_zones,slug,' . $zone->id],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'regions' => ['nullable', 'array'],
            'regions.*.type' => ['required_with:regions', 'in:state,city,zip'],
            'regions.*.value' => ['required_with:regions', 'string', 'max:100'],
        ]);

        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['color'] = $data['color'] ?? '#6366f1';

        $regions = $data['regions'] ?? [];
        unset($data['regions']);

        $zone->update($data);

        $zone->regions()->delete();
        $this->saveRegions($zone, $regions);

        return back()->with('status', 'Delivery zone updated successfully.');
    }

    public function destroy(DeliveryZone $zone)
    {
        $zone->delete();
        return back()->with('status', 'Delivery zone deleted successfully.');
    }

    public function toggleStatus(DeliveryZone $zone)
    {
        $zone->update(['status' => !$zone->status]);
        return back()->with('status', 'Delivery zone status updated.');
    }

    protected function saveRegions(DeliveryZone $zone, array $regions): void
    {
        $seen = [];

        foreach ($regions as $region) {
            if (empty($region['value']) || empty($region['type'])) {
                continue;
            }

            $value = trim($region['value']);
            $key = $region['type'] . ':' . strtolower($value);

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;

            $zone->regions()->create([
                'type' => $region['type'],
                'value' => $value,
            ]);
        }
    }
}

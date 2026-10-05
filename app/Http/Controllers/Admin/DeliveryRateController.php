<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\DeliveryRate;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryRateController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('status', 1)->orderByDesc('is_default')->orderBy('name')->get();
        $zones = DeliveryZone::where('status', 1)->orderBy('sort_order')->orderBy('name')->get();

        $selectedBranchId = $request->input('branch_id') ?: ($branches->firstWhere('is_default', true)?->id ?? $branches->first()?->id);

        $rates = DeliveryRate::with(['branch', 'zone'])
            ->where('branch_id', $selectedBranchId)
            ->get()
            ->keyBy('zone_id');

        return view('admin.delivery-rate.index', compact('branches', 'zones', 'rates', 'selectedBranchId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'zone_id' => ['required', 'exists:delivery_zones,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_days' => ['required', 'integer', 'min:0'],
            'max_days' => ['required', 'integer', 'min:0', 'gte:min_days'],
            'free_enabled' => ['nullable', 'boolean'],
            'free_type' => ['nullable', 'in:price,quantity'],
            'free_min_price' => ['nullable', 'numeric', 'min:0'],
            'free_min_qty' => ['nullable', 'integer', 'min:0'],
            'cod_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['free_enabled'] = $request->boolean('free_enabled');
        $data['cod_enabled'] = $request->boolean('cod_enabled', true);
        $data['status'] = $request->boolean('status', true);

        if (!$data['free_enabled']) {
            $data['free_type'] = 'price';
            $data['free_min_price'] = null;
            $data['free_min_qty'] = null;
        }

        DeliveryRate::updateOrCreate(
            [
                'branch_id' => $data['branch_id'],
                'zone_id' => $data['zone_id'],
            ],
            $data
        );

        return back()->with('status', 'Delivery rate saved successfully.');
    }

    public function update(Request $request, DeliveryRate $rate)
    {
        $data = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'min_days' => ['required', 'integer', 'min:0'],
            'max_days' => ['required', 'integer', 'min:0', 'gte:min_days'],
            'free_enabled' => ['nullable', 'boolean'],
            'free_type' => ['nullable', 'in:price,quantity'],
            'free_min_price' => ['nullable', 'numeric', 'min:0'],
            'free_min_qty' => ['nullable', 'integer', 'min:0'],
            'cod_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['free_enabled'] = $request->boolean('free_enabled');
        $data['cod_enabled'] = $request->boolean('cod_enabled');
        $data['status'] = $request->boolean('status');

        if (!$data['free_enabled']) {
            $data['free_type'] = 'price';
            $data['free_min_price'] = null;
            $data['free_min_qty'] = null;
        }

        $rate->update($data);

        return back()->with('status', 'Delivery rate updated successfully.');
    }

    public function destroy(DeliveryRate $rate)
    {
        $rate->delete();
        return back()->with('status', 'Delivery rate removed.');
    }

    public function toggleStatus(DeliveryRate $rate)
    {
        $rate->update(['status' => !$rate->status]);
        return back()->with('status', 'Rate status updated.');
    }
}

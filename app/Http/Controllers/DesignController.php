<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PrintZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected array $designableModels = [
        'DTF Transfers',
        'UV Stickers',
        'Special Films',
    ];

    public function index(Request $request, ?string $slug = null)
    {
        $zones = PrintZone::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        $product = null;

        if ($slug) {
            $product = Product::with([
                    'printZones',
                    'images',
                    'model',
                    'options' => function ($q) {
                        $q->where('status', 1);
                    },
                    'options.activeMeasurements',
                ])
                ->where('slug', $slug)
                ->where('status', 1)
                ->first();

            if ($product && $product->printZones->count()) {
                $zones = $product->printZones
                    ->where('status', 1)
                    ->sortBy('sort_order')
                    ->values();
            }
        }

        $zonesPayload = $zones->map(function (PrintZone $zone) {
            $w = (float) ($zone->max_width_inch ?: 12);
            $h = (float) ($zone->max_height_inch ?: 12);

            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'slug' => $zone->slug,
                'width_inch' => $w,
                'height_inch' => $h,
                'label' => $zone->name . ' (' . $w . ' × ' . $h . ' in)',
                'price_addon' => (float) $zone->price_addon,
            ];
        })->values();

        $allProducts = Product::with(['images', 'model'])
            ->where('status', 1)
            ->whereHas('model', function ($q) {
                $q->whereIn('name', $this->designableModels)
                  ->where('status', 1);
            })
            ->orderBy('name')
            ->get()
            ->map(function (Product $p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'base_price' => (float) ($p->sale_price ?: $p->base_price),
                    'image' => $p->images->first()?->url,
                    'print_type' => $p->print_type,
                    'model_name' => $p->model?->name,
                    'model_slug' => $p->model?->slug,
                ];
            })
            ->values();

        $productPayload = null;

        if ($product) {
            $productPayload = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'base_price' => (float) ($product->sale_price ?: $product->base_price),
                'image' => $product->images->first()?->url,
                'print_type' => $product->print_type,
                'model_name' => $product->model?->name,
                'model_slug' => $product->model?->slug,
                'measurements' => $this->measurementPayload($product),
            ];
        }

        $canvasState = $this->resolveCanvasState($request);

        return view('theme.rjshop-theme.staticpages.design', [
            'zones' => $zonesPayload,
            'product' => $productPayload,
            'allProducts' => $allProducts,
            'canvasState' => $canvasState,
            'adminMode' => $canvasState !== null,
        ]);
    }

    public function tempUpload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:png,jpg,jpeg,webp,pdf', 'max:30720'],
        ]);

        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed.',
            ], 422);
        }

        $sessionId = preg_replace('/[^a-zA-Z0-9]/', '', session()->getId());
        $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
        $ext = preg_replace('/[^a-z0-9]/', '', $ext) ?: 'png';

        $token = Str::random(16);
        $filename = $token . '.' . $ext;
        $dir = 'tmp/uploads/' . $sessionId;

        $path = $file->storeAs($dir, $filename, 'public');

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => 'Could not save file.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType() ?: 'application/octet-stream',
        ]);
    }

    protected function resolveCanvasState(Request $request): ?array
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();

        if (!isset($user->role) || $user->role !== 'admin') {
            return null;
        }

        $orderItemId = (int) $request->query('state');

        if ($orderItemId <= 0) {
            return null;
        }

        $item = OrderItem::find($orderItemId);

        if (!$item) {
            return null;
        }

        $breakdown = $item->price_breakdown ?? [];
        $state = $breakdown['canvas_state'] ?? null;

        if (!is_array($state) || empty($state)) {
            return null;
        }

        return [
            'order_item_id' => $item->id,
            'order_id' => $item->order_id,
            'product_name' => $item->product_name,
            'width_inch' => (float) $item->print_width,
            'height_inch' => (float) $item->print_height,
            'canvas' => $state,
        ];
    }

    protected function measurementPayload(Product $product): array
    {
        $unitLabels = ['inch' => 'in', 'feet' => 'ft', 'cm' => 'cm'];
        $list = [];

        foreach ($product->options as $option) {
            if ($option->type !== 'measurement') {
                continue;
            }

            $unit = $unitLabels[$option->measurement_unit] ?? 'in';

            foreach ($option->activeMeasurements as $m) {
                $w = (float) $m->width_value;
                $h = (float) $m->height_value;

                $list[] = [
                    'id' => $m->id,
                    'option_id' => $option->id,
                    'option_name' => $option->name,
                    'width' => $w,
                    'height' => $h,
                    'price' => (float) $m->price,
                    'unit' => $unit,
                    'is_default' => (bool) $m->is_default,
                    'label' => rtrim(rtrim(number_format($w, 2, '.', ''), '0'), '.')
                        . ' × '
                        . rtrim(rtrim(number_format($h, 2, '.', ''), '0'), '.')
                        . ' ' . $unit,
                ];
            }
        }

        return $list;
    }
}

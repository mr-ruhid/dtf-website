<?php

namespace App\Http\Controllers;

use App\Models\OrderDesign;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PrintZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function adminSave(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = auth()->user();

        if (!isset($user->role) || $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'composite_upload' => ['nullable', 'array'],
            'composite_upload.token' => ['nullable', 'string', 'max:64'],
            'composite_upload.path' => ['nullable', 'string', 'max:500'],
            'composite_upload.name' => ['nullable', 'string', 'max:255'],
            'composite_upload.size' => ['nullable', 'integer', 'min:0'],
            'composite_upload.mime' => ['nullable', 'string', 'max:100'],
            'composite_image' => ['nullable', 'string'],
            'canvas_state' => ['nullable', 'array'],
            'dpi' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ]);

        $item = OrderItem::with(['order', 'designs'])->find($validated['order_item_id']);

        if (!$item || !$item->order) {
            return response()->json(['success' => false, 'message' => 'Order item not found'], 404);
        }

        DB::beginTransaction();

        try {
            $newPath = null;
            $mime = 'image/png';
            $size = 0;

            if (!empty($validated['composite_upload']['path'])) {
                $srcPath = trim((string) $validated['composite_upload']['path']);

                if (str_starts_with($srcPath, 'tmp/uploads/') && !str_contains($srcPath, '..') && Storage::disk('public')->exists($srcPath)) {
                    $ext = strtolower(pathinfo($srcPath, PATHINFO_EXTENSION));
                    $ext = preg_replace('/[^a-z0-9]/', '', $ext) ?: 'png';

                    $newPath = 'orders/designs/' . $item->order_id . '-' . $item->id . '-' . Str::random(8) . '.' . $ext;

                    Storage::disk('public')->copy($srcPath, $newPath);

                    $size = (int) Storage::disk('public')->size($newPath);

                    if (!empty($validated['composite_upload']['mime'])) {
                        $mime = substr((string) $validated['composite_upload']['mime'], 0, 100);
                    }
                }
            }

            if (!$newPath && !empty($validated['composite_image'])) {
                $src = (string) $validated['composite_image'];

                if (str_starts_with($src, 'data:')) {
                    $parts = explode(',', $src, 2);

                    if (count($parts) === 2) {
                        $decoded = base64_decode($parts[1], true);

                        if ($decoded !== false) {
                            $newPath = 'orders/designs/' . $item->order_id . '-' . $item->id . '-' . Str::random(8) . '.png';
                            Storage::disk('public')->put($newPath, $decoded);
                            $size = strlen($decoded);
                        }
                    }
                }
            }

            if (!$newPath) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'No composite file provided'], 422);
            }

            $composite = $item->designs()->first();

            if ($composite) {
                if ($composite->file_path && !str_starts_with($composite->file_path, 'http')) {
                    Storage::disk('public')->delete($composite->file_path);
                }

                $composite->update([
                    'file_path' => $newPath,
                    'mime_type' => $mime,
                    'file_size' => $size,
                    'original_name' => $composite->original_name ?: ('admin-edit-' . $item->id . '.png'),
                ]);
            } else {
                OrderDesign::create([
                    'order_item_id' => $item->id,
                    'file_path' => $newPath,
                    'original_name' => 'admin-edit-' . $item->id . '.png',
                    'mime_type' => $mime,
                    'file_size' => $size,
                    'width' => $item->print_width,
                    'height' => $item->print_height,
                ]);
            }

            $breakdown = $item->price_breakdown ?? [];

            if (!empty($validated['canvas_state']) && is_array($validated['canvas_state'])) {
                $encoded = json_encode($validated['canvas_state']);

                if ($encoded !== false && strlen($encoded) <= 2097152) {
                    $breakdown['canvas_state'] = $validated['canvas_state'];
                }
            }

            if (!empty($validated['dpi'])) {
                $breakdown['admin_dpi'] = (int) $validated['dpi'];
            }

            $breakdown['admin_edited_at'] = now()->toIso8601String();
            $breakdown['admin_edited_by'] = $user->name ?? 'Admin';

            $item->update(['price_breakdown' => $breakdown]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('admin.orders.show', $item->order_id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin save failed: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Save failed: ' . $e->getMessage()], 500);
        }
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

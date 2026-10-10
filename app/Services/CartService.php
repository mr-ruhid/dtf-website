<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    protected string $sessionKey = 'rj_cart';

    public function all(): array
    {
        return session()->get($this->sessionKey, []);
    }

    public function add(array $data): string
    {
        $key = $this->makeKey($data);
        $cart = $this->all();

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += max(1, (int) ($data['qty'] ?? 1));
        } else {
            $cart[$key] = $this->buildItem($data, $key);
        }

        $this->recalculateItem($cart[$key]);

        session()->put($this->sessionKey, $cart);

        return $key;
    }

    public function update(string $key, int $qty): bool
    {
        $cart = $this->all();

        if (!isset($cart[$key])) {
            return false;
        }

        $qty = max(1, min(9999, $qty));
        $cart[$key]['qty'] = $qty;

        $this->recalculateItem($cart[$key]);

        session()->put($this->sessionKey, $cart);

        return true;
    }

    public function remove(string $key): bool
    {
        $cart = $this->all();

        if (!isset($cart[$key])) {
            return false;
        }

        unset($cart[$key]);
        session()->put($this->sessionKey, $cart);

        return true;
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function count(): int
    {
        return array_sum(array_column($this->all(), 'qty'));
    }

    public function subtotal(): float
    {
        return round(array_sum(array_column($this->all(), 'total')), 2);
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    protected function buildItem(array $data, string $key): array
    {
        $product = null;

        if (!empty($data['product_id'])) {
            $product = Product::with(['images', 'prices'])->find($data['product_id']);
        }

        $image = $product?->images->first()?->url
            ?? ($data['image'] ?? null);

        $name = $product?->name
            ?? ($data['name'] ?? 'Custom Design');

        $slug = $product?->slug
            ?? ($data['slug'] ?? null);

        $baseUnitPrice = round((float) ($data['unit_price'] ?? 0), 2);

        if ($product && $product->base_price > 0 && $baseUnitPrice <= 0) {
            $baseUnitPrice = (float) ($product->sale_price ?: $product->base_price);
        }

        $pricingType = $product?->pricing_type ?? 'fixed';
        $printType = $data['print_type'] ?? 'none';

        $widthInch = isset($data['width_inch']) && $data['width_inch'] !== null && $data['width_inch'] !== ''
            ? round((float) $data['width_inch'], 2)
            : null;

        $heightInch = isset($data['height_inch']) && $data['height_inch'] !== null && $data['height_inch'] !== ''
            ? round((float) $data['height_inch'], 2)
            : null;

        $sheetPrice = isset($data['sheet_price']) && $data['sheet_price'] !== null && $data['sheet_price'] !== ''
            ? round((float) $data['sheet_price'], 2)
            : null;

        $productPrice = isset($data['product_price']) && $data['product_price'] !== null && $data['product_price'] !== ''
            ? round((float) $data['product_price'], 2)
            : null;

        if ($productPrice === null && $product) {
            $productPrice = round((float) ($product->sale_price ?: $product->base_price), 2);
        }

        $fileName = isset($data['file_name']) && is_string($data['file_name']) && $data['file_name'] !== ''
            ? substr($data['file_name'], 0, 255)
            : null;

        $originalUploads = $this->sanitizeUploads($data['original_uploads'] ?? []);

        $compositeUpload = $this->sanitizeCompositeUpload($data['composite_upload'] ?? null);

        $compositeImage = null;

        if (!empty($data['composite_image']) && is_string($data['composite_image'])) {
            $raw = $data['composite_image'];

            if (strlen($raw) <= 52428800 && str_starts_with($raw, 'data:image/')) {
                $compositeImage = $raw;
            }
        }

        $canvasState = $this->sanitizeCanvasState($data['canvas_state'] ?? null);

        return [
            'key' => $key,
            'product_id' => $data['product_id'] ?? null,
            'name' => $name,
            'slug' => $slug,
            'image' => $image,
            'base_unit_price' => $baseUnitPrice,
            'unit_price' => $baseUnitPrice,
            'qty' => max(1, (int) ($data['qty'] ?? 1)),
            'total' => 0,
            'attributes' => $data['attributes'] ?? [],
            'options' => $data['options'] ?? [],
            'print_type' => $printType,
            'pricing_type' => $pricingType,
            'note' => $data['note'] ?? null,
            'tier_label' => null,
            'width_inch' => $widthInch,
            'height_inch' => $heightInch,
            'sheet_price' => $sheetPrice,
            'product_price' => $productPrice,
            'file_name' => $fileName,
            'original_uploads' => $originalUploads,
            'composite_upload' => $compositeUpload,
            'composite_image' => $compositeImage,
            'canvas_state' => $canvasState,
        ];
    }

    protected function sanitizeUploads($uploads): array
    {
        if (!is_array($uploads) || empty($uploads)) {
            return [];
        }

        $clean = [];

        foreach ($uploads as $u) {
            if (!is_array($u)) {
                continue;
            }

            $path = isset($u['path']) && is_string($u['path']) ? trim($u['path']) : '';

            if ($path === '') {
                continue;
            }

            if (str_contains($path, '..') || !str_starts_with($path, 'tmp/uploads/')) {
                continue;
            }

            $clean[] = [
                'token' => isset($u['token']) ? substr((string) $u['token'], 0, 64) : null,
                'path' => substr($path, 0, 500),
                'name' => isset($u['name']) ? substr((string) $u['name'], 0, 255) : null,
                'size' => isset($u['size']) ? max(0, (int) $u['size']) : 0,
                'mime' => isset($u['mime']) ? substr((string) $u['mime'], 0, 100) : null,
            ];
        }

        return $clean;
    }

    protected function sanitizeCompositeUpload($upload): ?array
    {
        if (!is_array($upload) || empty($upload)) {
            return null;
        }

        $path = isset($upload['path']) && is_string($upload['path']) ? trim($upload['path']) : '';

        if ($path === '') {
            return null;
        }

        if (str_contains($path, '..') || !str_starts_with($path, 'tmp/uploads/')) {
            return null;
        }

        return [
            'token' => isset($upload['token']) ? substr((string) $upload['token'], 0, 64) : null,
            'path' => substr($path, 0, 500),
            'name' => isset($upload['name']) ? substr((string) $upload['name'], 0, 255) : null,
            'size' => isset($upload['size']) ? max(0, (int) $upload['size']) : 0,
            'mime' => isset($upload['mime']) ? substr((string) $upload['mime'], 0, 100) : null,
        ];
    }

    protected function sanitizeCanvasState($state): ?array
    {
        if (!is_array($state) || empty($state)) {
            return null;
        }

        $encoded = json_encode($state);

        if ($encoded === false) {
            return null;
        }

        if (strlen($encoded) > 2097152) {
            return null;
        }

        return $state;
    }

    protected function recalculateItem(array &$item): void
    {
        $qty = max(1, (int) ($item['qty'] ?? 1));
        $baseUnitPrice = (float) ($item['base_unit_price'] ?? 0);

        if ($baseUnitPrice <= 0) {
            $baseUnitPrice = (float) ($item['unit_price'] ?? 0);
            $item['base_unit_price'] = $baseUnitPrice;
        }

        $productId = $item['product_id'] ?? null;
        $printType = $item['print_type'] ?? 'none';

        $pricingType = $item['pricing_type'] ?? null;

        if (!$pricingType && $productId) {
            $pricingType = Product::where('id', $productId)->value('pricing_type') ?: 'fixed';
            $item['pricing_type'] = $pricingType;
        }

        $pricingType = $pricingType ?: 'fixed';

        $tier = $this->resolveTier($productId, $qty);

        $unitPrice = $baseUnitPrice;

        if ($tier) {
            if ($pricingType === 'discount') {
                $pct = (float) $tier['value'];
                $unitPrice = round($baseUnitPrice * (1 - $pct / 100), 2);
            } else {
                if ($printType !== 'custom_size') {
                    $unitPrice = (float) $tier['value'];
                }
            }
            $item['tier_label'] = $tier['label'];
        } else {
            $item['tier_label'] = null;
        }

        $item['unit_price'] = round($unitPrice, 2);
        $item['total'] = round($item['unit_price'] * $qty, 2);
    }

    protected function resolveTier($productId, int $qty): ?array
    {
        if (!$productId) {
            return null;
        }

        $product = Product::with('prices')->find($productId);

        if (!$product) {
            return null;
        }

        $tiers = $product->prices->sortBy('min_qty');

        if ($tiers->isEmpty()) {
            return null;
        }

        $matched = null;

        foreach ($tiers as $tier) {
            $min = (int) $tier->min_qty;
            $max = $tier->max_qty !== null ? (int) $tier->max_qty : null;

            if ($qty >= $min && ($max === null || $qty <= $max)) {
                $matched = $tier;
                break;
            }
        }

        if (!$matched) {
            $matched = $tiers->first();
        }

        $rangeLabel = $matched->max_qty !== null
            ? $matched->min_qty . '–' . $matched->max_qty
            : $matched->min_qty . '+';

        $isDiscount = ($product->pricing_type ?? 'fixed') === 'discount';
        $value = (float) $matched->price;

        $label = $isDiscount
            ? $rangeLabel . ' qty · ' . rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.') . '% off'
            : $rangeLabel . ' qty · $' . number_format($value, 2) . ' ea';

        return [
            'value' => $value,
            'label' => $label,
            'is_discount' => $isDiscount,
        ];
    }

    protected function makeKey(array $data): string
    {
        $payload = [
            'product_id' => $data['product_id'] ?? 0,
            'name' => $data['name'] ?? null,
            'width_inch' => $data['width_inch'] ?? null,
            'height_inch' => $data['height_inch'] ?? null,
            'attributes' => $data['attributes'] ?? [],
            'options' => $data['options'] ?? [],
            'original_uploads' => $data['original_uploads'] ?? [],
        ];

        return md5(json_encode($payload));
    }
}

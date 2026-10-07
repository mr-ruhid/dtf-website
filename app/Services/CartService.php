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
            'print_type' => $data['print_type'] ?? 'none',
            'note' => $data['note'] ?? null,
            'tier_label' => null,
        ];
    }

    protected function recalculateItem(array &$item): void
    {
        $qty = max(1, (int) ($item['qty'] ?? 1));
        $unitPrice = (float) ($item['unit_price'] ?? 0);
        $baseUnitPrice = (float) ($item['base_unit_price'] ?? $unitPrice);

        if ($unitPrice <= 0) {
            $unitPrice = $baseUnitPrice;
        }

        $tier = $this->resolveTier($item['product_id'] ?? null, $qty);

        if ($tier) {
            $unitPrice = $tier['price'];
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
            $matched = $tiers->last();
        }

        $rangeLabel = $matched->max_qty !== null
            ? $matched->min_qty . '–' . $matched->max_qty
            : $matched->min_qty . '+';

        return [
            'price' => round((float) $matched->price, 2),
            'label' => $rangeLabel . ' qty · $' . number_format((float) $matched->price, 2) . ' ea',
        ];
    }

    protected function makeKey(array $data): string
    {
        $payload = [
            'product_id' => $data['product_id'] ?? 0,
            'name' => $data['name'] ?? null,
            'attributes' => $data['attributes'] ?? [],
            'options' => $data['options'] ?? [],
        ];

        return md5(json_encode($payload));
    }
}

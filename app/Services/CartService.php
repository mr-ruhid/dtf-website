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

        $cart[$key]['total'] = round($cart[$key]['unit_price'] * $cart[$key]['qty'], 2);

        session()->put($this->sessionKey, $cart);

        return $key;
    }

    public function update(string $key, int $qty): bool
    {
        $cart = $this->all();

        if (!isset($cart[$key])) {
            return false;
        }

        $qty = max(1, min(999, $qty));
        $cart[$key]['qty'] = $qty;
        $cart[$key]['total'] = round($cart[$key]['unit_price'] * $qty, 2);

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
        $product = Product::with('images')->find($data['product_id']);

        $image = $product?->images->first()?->url ?? ($data['image'] ?? null);
        $slug = $product?->slug ?? ($data['slug'] ?? null);

        return [
            'key' => $key,
            'product_id' => (int) $data['product_id'],
            'name' => $product?->name ?? ($data['name'] ?? 'Product'),
            'slug' => $slug,
            'image' => $image,
            'unit_price' => round((float) ($data['unit_price'] ?? 0), 2),
            'qty' => max(1, (int) ($data['qty'] ?? 1)),
            'total' => 0,
            'attributes' => $data['attributes'] ?? [],
            'options' => $data['options'] ?? [],
            'print_type' => $data['print_type'] ?? 'none',
            'note' => $data['note'] ?? null,
        ];
    }

    protected function makeKey(array $data): string
    {
        $payload = [
            'product_id' => $data['product_id'] ?? 0,
            'attributes' => $data['attributes'] ?? [],
            'options' => $data['options'] ?? [],
        ];

        return md5(json_encode($payload));
    }
}

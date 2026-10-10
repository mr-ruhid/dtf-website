<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function items()
    {
        return response()->json($this->payload());
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|integer|exists:products,id',
            'product_name' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'file_name' => 'nullable|string|max:255',
            'unit_price' => 'nullable|numeric|min:0',
            'product_price' => 'nullable|numeric|min:0',
            'sheet_price' => 'nullable|numeric|min:0',
            'width_inch' => 'nullable|numeric|min:0|max:1000',
            'height_inch' => 'nullable|numeric|min:0|max:1000',
            'qty' => 'nullable|integer|min:1|max:9999',
            'attributes' => 'nullable|array',
            'options' => 'nullable|array',
            'print_type' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        if (empty($validated['product_id']) && empty($validated['name'])) {
            return response()->json([
                'success' => false,
                'message' => 'Either product_id or name is required',
            ], 422);
        }

        $this->cart->add($validated);

        return response()->json($this->payload());
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:9999',
        ]);

        $this->cart->update($key, (int) $validated['qty']);

        return response()->json($this->payload());
    }

    public function remove(string $key)
    {
        $this->cart->remove($key);

        return response()->json($this->payload());
    }

    public function clear()
    {
        $this->cart->clear();

        return response()->json($this->payload());
    }

    protected function payload(): array
    {
        return [
            'success' => true,
            'items' => array_values($this->cart->all()),
            'count' => $this->cart->count(),
            'subtotal' => $this->cart->subtotal(),
        ];
    }
}

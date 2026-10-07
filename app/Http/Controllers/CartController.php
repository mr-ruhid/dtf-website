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

    public function index()
    {
        $items = $this->cart->all();
        $subtotal = $this->cart->subtotal();
        $count = $this->cart->count();

        return view('theme.rjshop-theme.staticpages.cart', compact('items', 'subtotal', 'count'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'unit_price' => 'required|numeric|min:0',
            'qty' => 'nullable|integer|min:1|max:999',
            'attributes' => 'nullable|array',
            'options' => 'nullable|array',
            'print_type' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        $key = $this->cart->add($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'key' => $key,
                'count' => $this->cart->count(),
                'subtotal' => $this->cart->subtotal(),
                'message' => 'Added to cart',
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Added to cart');
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:999',
        ]);

        $ok = $this->cart->update($key, (int) $validated['qty']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $ok,
                'count' => $this->cart->count(),
                'subtotal' => $this->cart->subtotal(),
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request, string $key)
    {
        $this->cart->remove($key);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $this->cart->count(),
                'subtotal' => $this->cart->subtotal(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed');
    }

    public function clear(Request $request)
    {
        $this->cart->clear();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'count' => 0, 'subtotal' => 0]);
        }

        return redirect()->route('cart.index');
    }

    public function count()
    {
        return response()->json([
            'count' => $this->cart->count(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }
}

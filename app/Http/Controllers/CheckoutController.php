<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use App\Models\Order;
use App\Payment\Registry\PaymentGatewayRegistry;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CartService $cart;

    protected OrderService $orders;

    protected PaymentGatewayRegistry $registry;

    public function __construct(
        CartService $cart,
        OrderService $orders,
        PaymentGatewayRegistry $registry
    ) {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->registry = $registry;
    }

    public function show()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('page.home')->with('checkout_error', 'Your cart is empty.');
        }

        $items = $this->cart->all();
        $subtotal = $this->cart->subtotal();
        $count = $this->cart->count();

        $gateways = $this->registry->enabled();

        if (empty($gateways)) {
            return redirect()->route('page.home')->with('checkout_error', 'No payment method is available right now.');
        }

        $zones = DeliveryZone::where('status', 1)->orderBy('sort_order')->get();

        return view('theme.rjshop-theme.staticpages.checkout', compact(
            'items', 'subtotal', 'count', 'gateways', 'zones'
        ));
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $validated = $request->validate([
            'customer.name' => 'required|string|max:100',
            'customer.email' => 'required|email|max:150',
            'customer.phone' => 'required|string|max:30',
            'customer.company' => 'nullable|string|max:100',

            'shipping.address' => 'required|string|max:255',
            'shipping.address2' => 'nullable|string|max:255',
            'shipping.city' => 'required|string|max:100',
            'shipping.state' => 'required|string|max:10',
            'shipping.zip' => 'required|string|max:20',
            'shipping.country' => 'nullable|string|max:5',

            'gateway_id' => 'required|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        $gateway = $this->registry->get($validated['gateway_id']);

        if (!$gateway || !$gateway->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Selected payment method is not available.',
            ], 422);
        }

        try {
            $order = $this->orders->createFromCart(
                $validated['customer'],
                $validated['shipping'],
                $validated['gateway_id'],
                $validated['note'] ?? null
            );

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'tracking_token' => $order->tracking_token,
                'redirect_url' => route('checkout.success', ['orderNumber' => $order->order_number]),
            ]);
        } catch (\Throwable $e) {
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong while placing your order.',
            ], 500);
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::with(['items.options', 'items.designs', 'statusLogs'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $gateway = $order->payment_gateway_id
            ? $this->registry->get($order->payment_gateway_id)
            : null;

        $instructions = null;
        $methods = [];
        $requiresReceipt = false;
        $receiptMaxSize = 5;
        $paymentWindowHours = 48;

        if ($gateway && method_exists($gateway, 'getInstructions')) {
            $instructions = $gateway->getInstructions();

            if (method_exists($gateway, 'getActiveMethods')) {
                $methods = $gateway->getActiveMethods();
            }

            if (method_exists($gateway, 'requiresReceipt')) {
                $requiresReceipt = $gateway->requiresReceipt();
            }

            if (method_exists($gateway, 'getReceiptMaxSizeMb')) {
                $receiptMaxSize = $gateway->getReceiptMaxSizeMb();
            }

            if (method_exists($gateway, 'getPaymentWindowHours')) {
                $paymentWindowHours = $gateway->getPaymentWindowHours();
            }
        }

        return view('theme.rjshop-theme.staticpages.checkout-success', compact(
            'order',
            'gateway',
            'instructions',
            'methods',
            'requiresReceipt',
            'receiptMaxSize',
            'paymentWindowHours'
        ));
    }
}

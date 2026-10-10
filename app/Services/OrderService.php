<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\DeliveryRate;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderDesign;
use App\Models\OrderItem;
use App\Models\OrderItemOption;
use App\Models\PaymentTransaction;
use App\Payment\Registry\PaymentGatewayRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cart;

    protected PaymentGatewayRegistry $registry;

    public function __construct(CartService $cart, PaymentGatewayRegistry $registry)
    {
        $this->cart = $cart;
        $this->registry = $registry;
    }

    public function createFromCart(array $customer, array $shipping, string $gatewayId, ?string $note = null): Order
    {
        if ($this->cart->isEmpty()) {
            throw new \RuntimeException('Cart is empty.');
        }

        $gateway = $this->registry->get($gatewayId);

        if (!$gateway || !$gateway->isEnabled()) {
            throw new \RuntimeException('Selected payment method is not available.');
        }

        $items = $this->cart->all();
        $subtotal = $this->cart->subtotal();
        $totalQty = $this->cart->count();

        $zone = DeliveryZone::findForLocation(
            $shipping['zip'] ?? null,
            $shipping['city'] ?? null,
            $shipping['state'] ?? null
        );

        $branch = Branch::getDefault();

        $deliveryCost = 0.0;
        $rate = null;

        if ($zone && $branch) {
            $rate = DeliveryRate::where('branch_id', $branch->id)
                ->where('zone_id', $zone->id)
                ->where('status', 1)
                ->first();

            if ($rate) {
                $deliveryCost = $rate->calculateCost($subtotal, $totalQty);
            }
        }

        $total = round($subtotal + $deliveryCost, 2);

        return DB::transaction(function () use (
            $customer, $shipping, $items, $gateway, $gatewayId,
            $subtotal, $deliveryCost, $total, $note,
            $zone, $branch
        ) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'tracking_token' => $this->generateTrackingToken(),

                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'company_name' => $customer['company'] ?? null,

                'shipping_address' => $shipping['address'],
                'shipping_address2' => $shipping['address2'] ?? null,
                'shipping_city' => $shipping['city'],
                'shipping_state' => $shipping['state'],
                'shipping_zip' => $shipping['zip'],
                'shipping_country' => $shipping['country'] ?? 'US',

                'branch_id' => $branch?->id,
                'zone_id' => $zone?->id,

                'subtotal' => $subtotal,
                'delivery_cost' => $deliveryCost,
                'discount' => 0,
                'tax' => 0,
                'total' => $total,

                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => 'manual',
                'payment_gateway_id' => $gatewayId,

                'customer_note' => $note,
                'source' => 'web',
            ]);

            foreach ($items as $row) {
                $printWidth = isset($row['width_inch']) && $row['width_inch'] !== null && $row['width_inch'] !== ''
                    ? (float) $row['width_inch']
                    : null;

                $printHeight = isset($row['height_inch']) && $row['height_inch'] !== null && $row['height_inch'] !== ''
                    ? (float) $row['height_inch']
                    : null;

                $rawImage = $row['image'] ?? null;
                $productImage = null;

                if (is_string($rawImage) && $rawImage !== '' && !Str::startsWith($rawImage, 'data:')) {
                    $productImage = $rawImage;
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product_id'] ?? null,
                    'product_name' => $row['name'],
                    'product_sku' => null,
                    'product_image' => $productImage,

                    'attributes' => $row['attributes'] ?? [],
                    'print_type' => $row['print_type'] ?? 'none',
                    'print_zone_id' => null,
                    'print_zone_name' => null,
                    'print_width' => $printWidth,
                    'print_height' => $printHeight,

                    'quantity' => $row['qty'],
                    'unit_price' => $row['unit_price'],
                    'total_price' => $row['total'],

                    'price_breakdown' => [
                        'unit_price' => $row['unit_price'],
                        'qty' => $row['qty'],
                        'total' => $row['total'],
                        'tier_label' => $row['tier_label'] ?? null,
                        'note' => $row['note'] ?? null,
                        'file_name' => $row['file_name'] ?? null,
                    ],
                ]);

                if (!empty($row['options']) && is_array($row['options'])) {
                    foreach ($row['options'] as $opt) {
                        OrderItemOption::create([
                            'order_item_id' => $orderItem->id,
                            'option_name' => $opt['option_name'] ?? '',
                            'option_value' => $opt['option_value'] ?? '',
                            'price_addon' => (float) ($opt['price_addon'] ?? 0),
                        ]);
                    }
                }

                $this->attachDesignIfAny($orderItem, $row);
            }

            $order->statusLogs()->create([
                'from_status' => null,
                'to_status' => 'pending',
                'note' => 'Order placed',
                'notify_customer' => 0,
                'is_public' => 1,
            ]);

            $gatewayResult = $gateway->createPayment($order);

            if (empty($gatewayResult['success'])) {
                throw new \RuntimeException($gatewayResult['message'] ?? 'Payment initialization failed.');
            }

            $transaction = PaymentTransaction::where('order_id', $order->id)
                ->where('gateway_id', $gatewayId)
                ->latest()
                ->first();

            if ($transaction) {
                $transaction->update([
                    'request_payload' => array_merge(
                        $transaction->request_payload ?? [],
                        [
                            'gateway_result' => $gatewayResult,
                            'zone_id' => $zone?->id,
                            'branch_id' => $branch?->id,
                        ]
                    ),
                ]);
            }

            $this->cart->clear();

            return $order->fresh(['items', 'statusLogs']);
        });
    }

    protected function attachDesignIfAny(OrderItem $item, array $row): void
    {
        if (empty($row['image'])) {
            return;
        }

        $src = $row['image'];

        if (!is_string($src) || !Str::startsWith($src, 'data:')) {
            return;
        }

        try {
            $parts = explode(',', $src, 2);

            if (count($parts) !== 2) {
                return;
            }

            $decoded = base64_decode($parts[1], true);

            if ($decoded === false) {
                return;
            }

            $mime = 'image/jpeg';
            $ext = 'jpg';

            if (preg_match('/^data:([^;]+);/', $src, $m)) {
                $detectedMime = strtolower($m[1]);
                $mime = $detectedMime;

                $map = [
                    'image/jpeg' => 'jpg',
                    'image/jpg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    'image/gif' => 'gif',
                    'application/pdf' => 'pdf',
                ];

                if (isset($map[$detectedMime])) {
                    $ext = $map[$detectedMime];
                }
            }

            $filename = 'orders/designs/' . $item->order_id . '-' . $item->id . '-' . Str::random(8) . '.' . $ext;

            Storage::disk('public')->put($filename, $decoded);

            $originalName = null;

            if (!empty($row['file_name']) && is_string($row['file_name'])) {
                $originalName = substr($row['file_name'], 0, 255);
            }

            OrderDesign::create([
                'order_item_id' => $item->id,
                'file_path' => $filename,
                'original_name' => $originalName,
                'mime_type' => $mime,
                'file_size' => strlen($decoded),
                'width' => $row['width_inch'] ?? null,
                'height' => $row['height_inch'] ?? null,
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Failed to attach design: ' . $e->getMessage());
        }
    }

    protected function generateTrackingToken(): string
    {
        do {
            $token = Str::random(32);
        } while (Order::where('tracking_token', $token)->exists());

        return $token;
    }
}

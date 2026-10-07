<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Payment\Registry\PaymentGatewayRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceiptController extends Controller
{
    protected PaymentGatewayRegistry $registry;

    public function __construct(PaymentGatewayRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function upload(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (!$order->payment_gateway_id) {
            return response()->json([
                'success' => false,
                'message' => 'This order has no payment gateway assigned.',
            ], 422);
        }

        $gateway = $this->registry->get($order->payment_gateway_id);

        if (!$gateway) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway is not available.',
            ], 422);
        }

        $maxSizeMb = method_exists($gateway, 'getReceiptMaxSizeMb')
            ? $gateway->getReceiptMaxSizeMb()
            : 5;

        $validated = $request->validate([
            'receipt' => [
                'required',
                'file',
                'max:' . ($maxSizeMb * 1024),
                'mimes:jpg,jpeg,png,webp,gif,pdf',
            ],
        ]);

        try {
            $file = $validated['receipt'];

            $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
            $safeExt = preg_replace('/[^a-z0-9]/', '', $ext) ?: 'bin';

            $filename = 'receipts/'
                . $order->order_number . '-'
                . now()->format('YmdHis') . '-'
                . Str::random(6) . '.'
                . $safeExt;

            Storage::disk('public')->putFileAs(
                dirname($filename),
                $file,
                basename($filename)
            );

            if (!Storage::disk('public')->exists($filename)) {
                throw new \RuntimeException('Failed to store uploaded file.');
            }

            $result = ['success' => true, 'message' => 'Receipt uploaded.'];

            if (method_exists($gateway, 'attachReceipt')) {
                $result = $gateway->attachReceipt(
                    $order,
                    $filename,
                    $file->getClientOriginalName(),
                    $file->getClientMimeType(),
                    $file->getSize()
                );
            }

            if (empty($result['success'])) {
                Storage::disk('public')->delete($filename);

                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Receipt could not be attached.',
                ], 500);
            }

            $order->statusLogs()->create([
                'from_status' => $order->status,
                'to_status' => $order->status,
                'note' => 'Customer uploaded payment receipt.',
                'notify_customer' => 0,
                'is_public' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Receipt uploaded successfully. Awaiting admin confirmation.',
                'receipt_path' => $filename,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Receipt upload failed: ' . $e->getMessage(), [
                'order_number' => $orderNumber,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

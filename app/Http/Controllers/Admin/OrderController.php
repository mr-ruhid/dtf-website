<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDesign;
use App\Models\Setting;
use App\Payment\Registry\PaymentGatewayRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected PaymentGatewayRegistry $registry;

    public function __construct(PaymentGatewayRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function index(Request $request)
    {
        $query = Order::with(['zone', 'branch'])->withCount('items');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::whereIn('status', ['confirmed', 'processing'])->count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        $branches = Branch::where('status', 1)->orderBy('name')->get();

        $notificationEmails = $this->getNotificationEmails();

        return view('admin.order.index', compact('orders', 'stats', 'branches', 'notificationEmails'));
    }

    public function show(Order $order)
    {
        $order->load([
            'items.product',
            'items.options',
            'items.designs',
            'items.printZone',
            'statusLogs',
            'transactions',
            'zone',
            'branch',
        ]);

        $gateway = $order->payment_gateway_id
            ? $this->registry->get($order->payment_gateway_id)
            : null;

        return view('admin.order.show', compact('order', 'gateway'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,shipped,delivered,cancelled,refunded'],
            'note' => ['nullable', 'string', 'max:500'],
            'notify_customer' => ['nullable', 'boolean'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $order->updateStatus(
            $data['status'],
            $data['note'] ?? null,
            auth()->id(),
            auth()->user()->name ?? 'Admin',
            $request->boolean('notify_customer'),
            $request->boolean('is_public', true)
        );

        return back()->with('status', 'Order status updated to ' . $order->fresh()->status_label . '.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:unpaid,paid,refunded'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $order->payment_status;
        $newStatus = $validated['payment_status'];

        $order->payment_status = $newStatus;

        if (array_key_exists('tracking_number', $validated)) {
            $order->tracking_number = $validated['tracking_number'];
        }

        if (array_key_exists('admin_note', $validated)) {
            $order->admin_note = $validated['admin_note'];
        }

        if ($newStatus === 'paid' && $oldStatus !== 'paid') {
            $order->confirmed_at = $order->confirmed_at ?: now();
        }

        if ($newStatus === 'refunded' && $oldStatus !== 'refunded') {
            $order->status = 'refunded';
        }

        $order->save();
        $order->refresh();

        $statusMsg = 'Payment status updated to ' . $order->payment_status_label . '.';

        if ($oldStatus !== $newStatus) {
            $statusMsg = 'Payment status changed from ' . ucfirst($oldStatus) . ' to ' . $order->payment_status_label . '.';
        }

        return back()->with('status', $statusMsg);
    }

    public function markAsPaid(Request $request, Order $order)
    {
        if (!$order->payment_gateway_id) {
            return back()->withErrors(['error' => 'This order has no payment gateway assigned.']);
        }

        $gateway = $this->registry->get($order->payment_gateway_id);

        if (!$gateway) {
            return back()->withErrors(['error' => 'Payment gateway is not available.']);
        }

        if (!method_exists($gateway, 'markAsPaid')) {
            return back()->withErrors(['error' => 'This gateway does not support manual payment marking.']);
        }

        $validated = $request->validate([
            'reference' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $gateway->markAsPaid(
                $order,
                $validated['reference'] ?? null,
                $validated['note'] ?? null
            );
        } catch (\Throwable $e) {
            logger()->error('markAsPaid failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to mark as paid: ' . $e->getMessage()]);
        }

        $order->refresh();

        $order->payment_status = 'paid';
        if (empty($order->confirmed_at)) {
            $order->confirmed_at = now();
        }
        if ($order->status === 'pending') {
            $order->status = 'confirmed';
        }
        $order->save();

        $order->updateStatus(
            'confirmed',
            'Payment confirmed by admin' . (!empty($validated['reference']) ? ' (ref: ' . $validated['reference'] . ')' : ''),
            auth()->id(),
            auth()->user()->name ?? 'Admin',
            true,
            true
        );

        app(\App\Services\OrderNotifier::class)->notifyPaymentConfirmed($order);

        return back()->with('status', 'Order marked as paid. Status confirmed.');
    }

    public function notificationEmails()
    {
        return response()->json([
            'success' => true,
            'emails' => $this->getNotificationEmails(),
        ]);
    }

    public function updateNotificationEmails(Request $request)
    {
        $validated = $request->validate([
            'emails' => ['nullable', 'array', 'max:50'],
            'emails.*' => ['nullable', 'email', 'max:150'],
        ]);

        $emails = collect($validated['emails'] ?? [])
            ->filter(fn($e) => is_string($e) && trim($e) !== '')
            ->map(fn($e) => strtolower(trim($e)))
            ->unique()
            ->values()
            ->all();

        Setting::set('order_notification_emails', json_encode($emails));

        return back()->with('status', 'Notification emails updated.');
    }

        public function testNotification()
    {
        $emails = $this->getNotificationEmails();

        if (empty($emails)) {
            return back()->withErrors(['error' => 'Add at least one notification email first.']);
        }

        $latest = Order::with(['items.options', 'items.designs', 'items.product', 'zone', 'branch'])
            ->latest()
            ->first();

        try {
            if ($latest) {
                app(\App\Services\OrderNotifier::class)->notifyNewOrder($latest);

                return back()->with('status', 'Test sent — latest order ' . $latest->order_number . ' notification was triggered to ' . count($emails) . ' recipient(s).');
            }

            \Illuminate\Support\Facades\Mail::to($emails)->send(
                new \App\Mail\TestNotification()
            );

            return back()->with('status', 'Test email sent to ' . count($emails) . ' recipient(s). No orders found yet.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Test failed: ' . $e->getMessage()]);
        }
    }

    protected function getNotificationEmails(): array
    {
        $raw = Setting::get('order_notification_emails');

        if (!$raw) {
            return [];
        }

        if (is_array($raw)) {
            return $raw;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function destroy(Order $order)
    {
        foreach ($order->items as $item) {
            foreach ($item->designs as $design) {
                if ($design->file_path && !str_starts_with($design->file_path, 'http')) {
                    Storage::disk('public')->delete($design->file_path);
                }
            }
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('status', 'Order deleted successfully.');
    }

    public function deleteDesign(Order $order, OrderDesign $design)
    {
        if ($design->file_path && !str_starts_with($design->file_path, 'http')) {
            Storage::disk('public')->delete($design->file_path);
        }

        $design->delete();

        return back()->with('status', 'Design file deleted.');
    }

    public function deleteReceipt(Order $order, int $transaction)
    {
        $txn = $order->transactions()->findOrFail($transaction);

        $payload = $txn->response_payload ?? [];
        $receipt = $payload['receipt'] ?? null;

        if ($receipt && !empty($receipt['file_path'])) {
            $path = $receipt['file_path'];

            if (!str_starts_with($path, 'http')) {
                Storage::disk('public')->delete($path);
            }
        }

        unset($payload['receipt']);

        $txn->update([
            'response_payload' => $payload,
            'status' => $txn->status === 'processing' ? 'pending' : $txn->status,
        ]);

        return back()->with('status', 'Receipt file deleted.');
    }

    public function apiDocs()
    {
        return view('admin.order.api-docs');
    }
}

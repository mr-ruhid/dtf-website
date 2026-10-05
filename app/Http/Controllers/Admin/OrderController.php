<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDesign;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
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

        return view('admin.order.index', compact('orders', 'stats', 'branches'));
    }

    public function show(Order $order)
    {
        $order->load([
            'items.product',
            'items.options',
            'items.designs',
            'items.printZone',
            'statusLogs',
            'zone',
            'branch',
        ]);

        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,shipped,delivered,cancelled,refunded'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $order->updateStatus(
            $data['status'],
            $data['note'] ?? null,
            auth()->id(),
            auth()->user()->name ?? 'Admin'
        );

        return back()->with('status', 'Order status updated to ' . $order->fresh()->status_label . '.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_status' => ['required', 'in:unpaid,paid,refunded'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update($data);

        return back()->with('status', 'Payment information updated.');
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

    public function apiDocs()
    {
        return view('admin.order.api-docs');
    }
}

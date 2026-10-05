<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 1)->count(),
            'open_tickets' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'urgent_tickets' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $recentOrders = Order::with(['zone', 'branch'])
            ->latest()
            ->limit(6)
            ->get();

        $recentTickets = SupportTicket::latest()
            ->limit(6)
            ->get();

        $topProducts = Product::with(['model', 'category', 'images'])
            ->where('status', 1)
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentTickets', 'topProducts'));
    }
}

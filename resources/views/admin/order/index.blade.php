@extends('admin.app')

@section('title', 'Orders')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Orders</h2>
        <p class="text-sm text-gray-500 mt-1">Manage all customer orders</p>
    </div>
    <a href="{{ route('admin.orders.api-docs') }}"
       class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
        <i class="fa-solid fa-code text-xs"></i> API Documentation
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total</span>
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-receipt text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
        <p class="text-[11px] text-gray-400 mt-1">All orders</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Pending</span>
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-clock text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-amber-600">{{ $stats['pending'] ?? 0 }}</div>
        <p class="text-[11px] text-gray-400 mt-1">Awaiting confirmation</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Processing</span>
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-gears text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-indigo-600">{{ $stats['processing'] ?? 0 }}</div>
        <p class="text-[11px] text-gray-400 mt-1">In progress</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Revenue</span>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-emerald-600">${{ number_format($stats['revenue'] ?? 0, 2) }}</div>
        <p class="text-[11px] text-gray-400 mt-1">Paid orders</p>
    </div>

</div>

@if (session('status'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

        <div>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Order #, name, email, phone..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                @foreach (\App\Models\Order::$statuses as $key => $meta)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Payments</option>
                @foreach (\App\Models\Order::$paymentStatuses as $key => $meta)
                    <option value="{{ $key }}" {{ request('payment_status') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="branch_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Branches</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
            </button>
            @if (request()->hasAny(['search', 'status', 'payment_status', 'branch_id']))
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>
</div>

@if ($orders->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Order</th>
                        <th class="px-4 py-3 text-left font-medium">Customer</th>
                        <th class="px-4 py-3 text-left font-medium">Items</th>
                        <th class="px-4 py-3 text-left font-medium">Location</th>
                        <th class="px-4 py-3 text-left font-medium">Total</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Payment</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-xs font-semibold text-indigo-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-800 truncate max-w-[180px]">{{ $order->customer_name }}</p>
                                <p class="text-[10px] text-gray-500 truncate max-w-[180px]">{{ $order->customer_email }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full font-medium">{{ $order->items_count }} items</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-gray-700">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                                @if ($order->zone)
                                    <p class="text-[10px] text-gray-400">{{ $order->zone->name }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">${{ number_format($order->total, 2) }}</p>
                                @if ($order->delivery_cost > 0)
                                    <p class="text-[10px] text-gray-400">+${{ number_format($order->delivery_cost, 2) }} ship</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold
                                    @if($order->status_color === 'amber') bg-amber-100 text-amber-700
                                    @elseif($order->status_color === 'blue') bg-blue-100 text-blue-700
                                    @elseif($order->status_color === 'indigo') bg-indigo-100 text-indigo-700
                                    @elseif($order->status_color === 'purple') bg-purple-100 text-purple-700
                                    @elseif($order->status_color === 'emerald') bg-emerald-100 text-emerald-700
                                    @elseif($order->status_color === 'rose') bg-rose-100 text-rose-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold
                                    @if($order->payment_status_color === 'amber') bg-amber-100 text-amber-700
                                    @elseif($order->payment_status_color === 'emerald') bg-emerald-100 text-emerald-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition" title="View details">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                          onsubmit="return confirm('Delete this order? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
@else
    <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
        <i class="fa-solid fa-receipt text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 text-sm mb-1">No orders yet</p>
        <p class="text-gray-400 text-xs">Orders from your mobile app will appear here automatically.</p>
    </div>
@endif

@endsection

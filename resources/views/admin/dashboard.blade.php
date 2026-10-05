@extends('admin.app')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-5 text-white relative overflow-hidden">
        <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-6 -right-2 w-20 h-20 rounded-full bg-white/5"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-indigo-100 uppercase tracking-wider">Total Revenue</span>
                <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur">
                    <i class="fa-solid fa-dollar-sign text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
            <div class="flex items-center gap-1 text-[11px] text-indigo-100 mt-1">
                <i class="fa-solid fa-arrow-trend-up text-[9px]"></i>
                <span>All time</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Orders</span>
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-receipt text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] ?? 0 }}</div>
        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-1">
            <span class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                {{ $stats['pending_orders'] ?? 0 }} pending
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Products</span>
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-cube text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['total_products'] ?? 0 }}</div>
        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-1">
            <span class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                {{ $stats['active_products'] ?? 0 }} active
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Support</span>
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-headset text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['open_tickets'] ?? 0 }}</div>
        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-1">
            <span class="flex items-center gap-1">
                @if (($stats['urgent_tickets'] ?? 0) > 0)
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    {{ $stats['urgent_tickets'] }} urgent
                @else
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                    No urgent
                @endif
            </span>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo-500 text-sm"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Recent Orders</h2>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                View all <i class="fa-solid fa-arrow-right text-[9px] ml-0.5"></i>
            </a>
        </div>

        @if ($recentOrders->count())
            <div class="divide-y divide-gray-100">
                @foreach ($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0
                            @if($order->status_color === 'amber') bg-amber-50 text-amber-600
                            @elseif($order->status_color === 'blue') bg-blue-50 text-blue-600
                            @elseif($order->status_color === 'indigo') bg-indigo-50 text-indigo-600
                            @elseif($order->status_color === 'purple') bg-purple-50 text-purple-600
                            @elseif($order->status_color === 'emerald') bg-emerald-50 text-emerald-600
                            @elseif($order->status_color === 'rose') bg-rose-50 text-rose-600
                            @else bg-gray-100 text-gray-600 @endif">
                            <i class="fa-solid fa-receipt text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-mono text-xs font-semibold text-gray-800">{{ $order->order_number }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ $order->customer_name }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-semibold text-sm text-gray-800">${{ number_format($order->total, 2) }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold shrink-0
                            @if($order->status_color === 'amber') bg-amber-100 text-amber-700
                            @elseif($order->status_color === 'blue') bg-blue-100 text-blue-700
                            @elseif($order->status_color === 'indigo') bg-indigo-100 text-indigo-700
                            @elseif($order->status_color === 'purple') bg-purple-100 text-purple-700
                            @elseif($order->status_color === 'emerald') bg-emerald-100 text-emerald-700
                            @elseif($order->status_color === 'rose') bg-rose-100 text-rose-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $order->status_label }}
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center">
                <i class="fa-solid fa-receipt text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">No orders yet</p>
                <p class="text-gray-400 text-xs mt-1">Orders from the mobile app will appear here</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bolt text-indigo-500 text-sm"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Quick Actions</h2>
            </div>
        </div>

        <div class="p-3 space-y-1">
            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-indigo-50 transition group">
                <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-500 group-hover:text-white transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">New Product</p>
                    <p class="text-[11px] text-gray-500">Add a product to your catalog</p>
                </div>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-amber-50 transition group">
                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-white transition">
                    <i class="fa-solid fa-receipt text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">View Orders</p>
                    <p class="text-[11px] text-gray-500">Manage customer orders</p>
                </div>
            </a>

            <a href="{{ route('admin.support.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-rose-50 transition group">
                <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 group-hover:bg-rose-500 group-hover:text-white transition">
                    <i class="fa-solid fa-headset text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">Support Tickets</p>
                    <p class="text-[11px] text-gray-500">Handle customer issues</p>
                </div>
            </a>

            <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-emerald-50 transition group">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition">
                    <i class="fa-solid fa-newspaper text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">Write Post</p>
                    <p class="text-[11px] text-gray-500">Publish a blog article</p>
                </div>
            </a>

            <a href="{{ route('admin.settings.general') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-100 transition group">
                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-slate-700 group-hover:text-white transition">
                    <i class="fa-solid fa-gear text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">Settings</p>
                    <p class="text-[11px] text-gray-500">Configure your store</p>
                </div>
            </a>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-fire text-rose-500 text-sm"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Recent Support Tickets</h2>
            </div>
            <a href="{{ route('admin.support.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                View all <i class="fa-solid fa-arrow-right text-[9px] ml-0.5"></i>
            </a>
        </div>

        @if ($recentTickets->count())
            <div class="divide-y divide-gray-100">
                @foreach ($recentTickets as $ticket)
                    <a href="{{ route('admin.support.show', $ticket) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0
                            @if($ticket->category_color === 'blue') bg-blue-50 text-blue-600
                            @elseif($ticket->category_color === 'emerald') bg-emerald-50 text-emerald-600
                            @elseif($ticket->category_color === 'indigo') bg-indigo-50 text-indigo-600
                            @elseif($ticket->category_color === 'purple') bg-purple-50 text-purple-600
                            @elseif($ticket->category_color === 'amber') bg-amber-50 text-amber-600
                            @elseif($ticket->category_color === 'cyan') bg-cyan-50 text-cyan-600
                            @else bg-gray-100 text-gray-600 @endif">
                            <i class="fa-solid {{ $ticket->category_icon }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $ticket->subject }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ $ticket->customer_name }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold
                                @if($ticket->status_color === 'rose') bg-rose-100 text-rose-700
                                @elseif($ticket->status_color === 'amber') bg-amber-100 text-amber-700
                                @elseif($ticket->status_color === 'blue') bg-blue-100 text-blue-700
                                @elseif($ticket->status_color === 'emerald') bg-emerald-100 text-emerald-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $ticket->status_label }}
                            </span>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center">
                <i class="fa-solid fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">No tickets yet</p>
                <p class="text-gray-400 text-xs mt-1">All clear!</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-star text-amber-500 text-sm"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Top Products</h2>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                View all <i class="fa-solid fa-arrow-right text-[9px] ml-0.5"></i>
            </a>
        </div>

        @if ($topProducts->count())
            <div class="divide-y divide-gray-100">
                @foreach ($topProducts as $product)
                    <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                        <div class="w-11 h-11 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                            @if ($product->images->first())
                                <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-cube text-xs"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate">
                                {{ $product->model->name ?? 'No model' }}
                                @if ($product->category) · {{ $product->category->name }} @endif
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-semibold text-sm text-gray-800">${{ number_format($product->base_price, 2) }}</p>
                            @if ($product->is_featured)
                                <span class="text-[10px] text-amber-600 font-medium flex items-center gap-1 justify-end">
                                    <i class="fa-solid fa-star text-[8px]"></i> Featured
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center">
                <i class="fa-solid fa-cube text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">No products yet</p>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add first product
                </a>
            </div>
        @endif
    </div>

</div>

@endsection

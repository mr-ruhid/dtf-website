@extends('admin.app')

@section('title', 'Order ' . $order->order_number)

@section('content')

<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to orders
        </a>
        <div class="flex items-center gap-3 mt-2 flex-wrap">
            <h2 class="text-xl font-semibold text-gray-800 font-mono">{{ $order->order_number }}</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold
                @if($order->status_color === 'amber') bg-amber-100 text-amber-700
                @elseif($order->status_color === 'blue') bg-blue-100 text-blue-700
                @elseif($order->status_color === 'indigo') bg-indigo-100 text-indigo-700
                @elseif($order->status_color === 'purple') bg-purple-100 text-purple-700
                @elseif($order->status_color === 'emerald') bg-emerald-100 text-emerald-700
                @elseif($order->status_color === 'rose') bg-rose-100 text-rose-700
                @else bg-gray-100 text-gray-700 @endif">
                {{ $order->status_label }}
            </span>
            @if($order->payment_status === 'paid')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Paid
                </span>
            @endif
        </div>
        <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>

    <div class="flex items-center gap-2 flex-wrap">
        @if ($order->tracking_token)
            <button type="button"
                    onclick="copyTrackLink(this)"
                    data-link="{{ route('track.show', ['token' => $order->tracking_token]) }}"
                    class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                <i class="fa-solid fa-link text-xs"></i>
                <span>Copy Tracking Link</span>
            </button>
        @endif
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
              onsubmit="return confirm('Delete this order permanently?')">
            @csrf
            @method('DELETE')
            <button class="bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                <i class="fa-solid fa-trash text-xs"></i> Delete
            </button>
        </form>
    </div>
</div>

@if (session('status'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
        {{ $errors->first() }}
    </div>
@endif

@php
    $receiptTxn = $order->transactions->firstWhere('response_payload.receipt', '!=', null);
    if (!$receiptTxn) {
        $receiptTxn = $order->transactions->first(function ($t) {
            $p = $t->response_payload ?? [];
            return !empty($p['receipt']);
        });
    }
    $receipt = $receiptTxn ? ($receiptTxn->response_payload['receipt'] ?? null) : null;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

        @if ($receipt)
            @php
                $receiptUrl = str_starts_with($receipt['file_path'], 'http')
                    ? $receipt['file_path']
                    : asset('storage/' . $receipt['file_path']);
                $isImage = !empty($receipt['mime_type']) && str_starts_with($receipt['mime_type'], 'image/');
            @endphp

            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-300 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-amber-200 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                            <i class="fa-solid fa-receipt text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-amber-900 text-sm">Payment Receipt Uploaded</h3>
                            <p class="text-[11px] text-amber-700">
                                Uploaded {{ !empty($receipt['uploaded_at']) ? \Carbon\Carbon::parse($receipt['uploaded_at'])->format('d M Y, H:i') : 'recently' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ $receiptUrl }}" target="_blank"
                           class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                            <i class="fa-solid fa-eye text-[10px]"></i> View
                        </a>
                        <a href="{{ $receiptUrl }}" download
                           class="inline-flex items-center gap-2 bg-white hover:bg-amber-50 border border-amber-300 text-amber-700 text-xs font-semibold px-3 py-2 rounded-lg transition">
                            <i class="fa-solid fa-download text-[10px]"></i> Download
                        </a>
                        <form method="POST" action="{{ route('admin.orders.receipt.delete', [$order, $receiptTxn->id]) }}"
                              onsubmit="return confirm('Delete this receipt file?')">
                            @csrf
                            @method('DELETE')
                            <button class="inline-flex items-center gap-2 bg-white hover:bg-red-50 border border-red-300 text-red-600 text-xs font-semibold px-3 py-2 rounded-lg transition">
                                <i class="fa-solid fa-trash text-[10px]"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex gap-5 flex-wrap">
                        <div class="shrink-0">
                            @if ($isImage)
                                <a href="{{ $receiptUrl }}" target="_blank"
                                   class="block w-48 h-48 rounded-lg border-2 border-amber-200 overflow-hidden bg-white hover:border-amber-400 transition">
                                    <img src="{{ $receiptUrl }}" class="w-full h-full object-contain">
                                </a>
                            @else
                                <a href="{{ $receiptUrl }}" target="_blank"
                                   class="block w-48 h-48 rounded-lg border-2 border-amber-200 bg-white flex flex-col items-center justify-center hover:border-amber-400 transition">
                                    <i class="fa-solid fa-file-pdf text-4xl text-red-500 mb-2"></i>
                                    <p class="text-xs text-gray-600 font-semibold">PDF Document</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Click to open</p>
                                </a>
                            @endif
                        </div>

                        <div class="flex-1 min-w-[200px] space-y-3">
                            <div>
                                <p class="text-[10px] text-amber-800 uppercase tracking-wider font-semibold mb-0.5">Original Name</p>
                                <p class="text-sm text-gray-800 break-all">{{ $receipt['original_name'] ?? 'N/A' }}</p>
                            </div>

                            @if (!empty($receipt['file_size']))
                                <div>
                                    <p class="text-[10px] text-amber-800 uppercase tracking-wider font-semibold mb-0.5">File Size</p>
                                    <p class="text-sm text-gray-800 font-mono">
                                        @if ($receipt['file_size'] < 1024)
                                            {{ $receipt['file_size'] }} B
                                        @elseif ($receipt['file_size'] < 1024 * 1024)
                                            {{ number_format($receipt['file_size'] / 1024, 1) }} KB
                                        @else
                                            {{ number_format($receipt['file_size'] / 1024 / 1024, 2) }} MB
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if ($order->payment_status !== 'paid')
                                <div class="pt-3 border-t border-amber-200">
                                    <p class="text-[11px] text-amber-800 mb-2">
                                        <i class="fa-solid fa-circle-info mr-1"></i>
                                        Verify the receipt then confirm payment below.
                                    </p>
                                </div>
                            @else
                                <div class="pt-3 border-t border-amber-200">
                                    <p class="text-[11px] text-emerald-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span class="font-semibold">Payment already confirmed</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($order->payment_status !== 'paid' && $gateway && method_exists($gateway, 'markAsPaid'))
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-300 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-money-bill-wave text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-emerald-900 mb-1">Confirm Payment Received</h3>
                        <p class="text-xs text-emerald-700 mb-4">
                            Mark this order as paid to confirm it and move it to processing.
                        </p>

                        <form method="POST" action="{{ route('admin.orders.mark-paid', $order) }}"
                              onsubmit="return confirm('Mark this order as PAID?')"
                              class="space-y-3">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-emerald-900 mb-1">Reference / Txn ID</label>
                                    <input type="text" name="reference" maxlength="100"
                                           placeholder="e.g. Bank ref, TXN-123"
                                           class="w-full px-3 py-2 border border-emerald-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-emerald-900 mb-1">Note (optional)</label>
                                    <input type="text" name="note" maxlength="500"
                                           placeholder="e.g. Verified via bank statement"
                                           class="w-full px-3 py-2 border border-emerald-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Mark as Paid & Confirm Order</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-box text-indigo-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Items ({{ $order->items->count() }})</h3>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <div class="p-5">
                        <div class="flex gap-4">

                            <div class="w-20 h-20 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                                @if ($item->product_image)
                                    @php
                                        $imgSrc = str_starts_with($item->product_image, 'data:image')
                                            ? $item->product_image
                                            : (str_starts_with($item->product_image, 'http')
                                                ? $item->product_image
                                                : asset('storage/' . $item->product_image));
                                    @endphp
                                    <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                                @elseif ($item->product && $item->product->primary_image)
                                    <img src="{{ $item->product->primary_image->url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-800">{{ $item->product_name }}</h4>
                                        @if ($item->product_sku)
                                            <p class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $item->product_sku }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="font-bold text-gray-800">${{ number_format($item->total_price, 2) }}</p>
                                        <p class="text-[10px] text-gray-400">${{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                                    </div>
                                </div>

                                @if ($item->attributes)
                                    <div class="flex flex-wrap gap-1.5 mt-3">
                                        @foreach ($item->attributes as $key => $value)
                                            <span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                                {{ $key }}: <span class="font-semibold">{{ $value }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($item->options->count())
                                    <div class="mt-3 pt-3 border-t border-gray-100 space-y-1">
                                        @foreach ($item->options as $option)
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-600">
                                                    <span class="font-medium">{{ $option->option_name }}:</span>
                                                    {{ $option->option_value }}
                                                </span>
                                                @if ($option->price_addon > 0)
                                                    <span class="text-[10px] text-indigo-600 font-semibold">+${{ number_format($option->price_addon, 2) }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($item->designs->count())
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold mb-2">
                                            <i class="fa-solid fa-file-image text-indigo-500 mr-1"></i>
                                            Design Files ({{ $item->designs->count() }})
                                        </p>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                            @foreach ($item->designs as $design)
                                                <div class="group relative rounded-lg border border-gray-200 overflow-hidden bg-slate-50">
                                                    @if ($design->exists && !$design->is_expired)
                                                        <a href="{{ $design->file_url }}" target="_blank" class="block aspect-square">
                                                            <img src="{{ $design->file_url }}" class="w-full h-full object-contain p-1">
                                                        </a>
                                                        <div class="p-1.5 bg-white border-t border-gray-100">
                                                            <p class="text-[9px] text-gray-500 truncate">{{ $design->original_name }}</p>
                                                            <p class="text-[9px] text-gray-400">{{ $design->file_size_human }}</p>
                                                        </div>
                                                        <form method="POST" action="{{ route('admin.orders.designs.destroy', [$order, $design]) }}"
                                                              onsubmit="return confirm('Delete this design file?')"
                                                              class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700">
                                                                <i class="fa-solid fa-trash text-[9px]"></i>
                                                            </button>
                                                        </form>
                                                    @elseif ($design->is_expired)
                                                        <div class="aspect-square flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                                            <i class="fa-solid fa-clock-rotate-left text-lg mb-1"></i>
                                                            <p class="text-[9px]">Expired</p>
                                                        </div>
                                                    @else
                                                        <div class="aspect-square flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                                            <i class="fa-solid fa-triangle-exclamation text-lg mb-1"></i>
                                                            <p class="text-[9px]">Missing</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-gray-200 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Delivery</span>
                    <span class="font-medium text-gray-800">
                        @if ($order->delivery_cost > 0)
                            ${{ number_format($order->delivery_cost, 2) }}
                        @else
                            <span class="text-emerald-600 font-semibold">Free</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-300">
                    <span class="font-semibold text-gray-800">Total</span>
                    <span class="font-bold text-lg text-indigo-600">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if ($order->transactions->count())
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-indigo-500 text-sm"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Payment Transactions ({{ $order->transactions->count() }})</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach ($order->transactions as $txn)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider
                                        @if($txn->status_color === 'amber') bg-amber-100 text-amber-700
                                        @elseif($txn->status_color === 'blue') bg-blue-100 text-blue-700
                                        @elseif($txn->status_color === 'emerald') bg-emerald-100 text-emerald-700
                                        @elseif($txn->status_color === 'rose') bg-rose-100 text-rose-700
                                        @elseif($txn->status_color === 'purple') bg-purple-100 text-purple-700
                                        @elseif($txn->status_color === 'indigo') bg-indigo-100 text-indigo-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $txn->status_label }}
                                    </span>
                                    <span class="text-xs font-mono text-gray-500">#{{ $txn->id }}</span>
                                </div>
                                <span class="font-bold text-gray-800">${{ number_format($txn->amount, 2) }}</span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Gateway</p>
                                    <p class="font-medium text-gray-800">{{ $txn->gateway_id }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Mode</p>
                                    <p class="font-medium text-gray-800 uppercase">{{ $txn->mode }}</p>
                                </div>
                                @if ($txn->reference_id)
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Reference</p>
                                        <p class="font-medium text-gray-800 font-mono text-[11px]">{{ $txn->reference_id }}</p>
                                    </div>
                                @endif
                                @if ($txn->paid_at)
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Paid</p>
                                        <p class="font-medium text-emerald-600">{{ $txn->paid_at->format('d M Y, H:i') }}</p>
                                    </div>
                                @endif
                            </div>

                            @if ($txn->refunded_amount > 0)
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Refunded</span>
                                    <span class="font-semibold text-rose-600">${{ number_format($txn->refunded_amount, 2) }}</span>
                                </div>
                            @endif

                            @if ($txn->error_message)
                                <div class="mt-3 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-700">
                                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                                    {{ $txn->error_message }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Status History</h3>
            </div>

            <div class="p-6">
                @if ($order->statusLogs->count())
                    <div class="relative pl-6">
                        <div class="absolute left-2 top-2 bottom-2 w-px bg-gray-200"></div>
                        @foreach ($order->statusLogs as $log)
                            <div class="relative pb-5 last:pb-0">
                                <div class="absolute -left-4 top-1 w-3 h-3 rounded-full border-2 border-white
                                    @if($log->to_status_color === 'amber') bg-amber-500
                                    @elseif($log->to_status_color === 'blue') bg-blue-500
                                    @elseif($log->to_status_color === 'indigo') bg-indigo-500
                                    @elseif($log->to_status_color === 'purple') bg-purple-500
                                    @elseif($log->to_status_color === 'emerald') bg-emerald-500
                                    @elseif($log->to_status_color === 'rose') bg-rose-500
                                    @else bg-gray-400 @endif"></div>
                                <div class="ml-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if ($log->from_status)
                                            <span class="text-[10px] text-gray-400 line-through">{{ $log->from_status_label }}</span>
                                            <i class="fa-solid fa-arrow-right text-[8px] text-gray-400"></i>
                                        @endif
                                        <span class="text-xs font-semibold text-gray-800">{{ $log->to_status_label }}</span>
                                        @if ($log->is_public)
                                            <span class="text-[9px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded font-semibold">PUBLIC</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-0.5">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                        @if ($log->changed_by_name)
                                            · by {{ $log->changed_by_name }}
                                        @endif
                                    </p>
                                    @if ($log->note)
                                        <p class="text-xs text-gray-600 mt-1 italic">"{{ $log->note }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 text-center py-4">No status changes yet</p>
                @endif
            </div>
        </div>

    </div>

    <div class="space-y-6">

        @if ($order->tracking_token)
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                    <h3 class="font-semibold text-indigo-900 text-sm">Tracking Link</h3>
                </div>

                <input type="text"
                       readonly
                       value="{{ route('track.show', ['token' => $order->tracking_token]) }}"
                       id="trackingUrl"
                       class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-[11px] font-mono text-gray-700 focus:outline-none mb-3">

                <div class="flex gap-2">
                    <button type="button"
                            onclick="copyTrackLink(this)"
                            data-link="{{ route('track.show', ['token' => $order->tracking_token]) }}"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-copy text-[10px]"></i>
                        <span>Copy</span>
                    </button>
                    <a href="{{ route('track.show', ['token' => $order->tracking_token]) }}"
                       target="_blank"
                       class="flex-1 bg-white hover:bg-indigo-50 border border-indigo-300 text-indigo-700 text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <span>Open</span>
                    </a>
                </div>

                <p class="text-[10px] text-indigo-700 mt-3 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Customer can use this link to track order status without logging in.
                </p>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-progress text-indigo-500"></i> Update Status
            </h3>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">New Status</label>
                    <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach (\App\Models\Order::$statuses as $key => $meta)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Note (optional)</label>
                    <textarea name="note" rows="2" maxlength="500"
                              placeholder="Internal note..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                        <input type="checkbox" name="is_public" value="1" checked
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Visible to customer</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                        <input type="checkbox" name="notify_customer" value="1"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Email customer</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-check text-xs mr-1"></i> Update Status
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-indigo-500"></i> Payment & Tracking
            </h3>

            <form method="POST" action="{{ route('admin.orders.payment', $order) }}" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Status</label>
                    <select name="payment_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach (\App\Models\Order::$paymentStatuses as $key => $meta)
                            <option value="{{ $key }}" {{ $order->payment_status === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                           placeholder="e.g. 1Z999AA10123456784"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Admin Note (internal)</label>
                    <textarea name="admin_note" rows="2" maxlength="1000"
                              placeholder="Internal notes..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('admin_note', $order->admin_note) }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Save Payment Info
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-user text-indigo-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Customer</h3>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Name</p>
                    <p class="font-medium text-gray-800">{{ $order->customer_name }}</p>
                </div>
                @if ($order->company_name)
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Company</p>
                        <p class="font-medium text-gray-800">{{ $order->company_name }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Email</p>
                    <a href="mailto:{{ $order->customer_email }}" class="text-indigo-600 hover:underline break-all">{{ $order->customer_email }}</a>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Phone</p>
                    <a href="tel:{{ $order->customer_phone }}" class="text-indigo-600 hover:underline">{{ $order->customer_phone }}</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-indigo-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Shipping Address</h3>
            </div>
            <div class="p-5 text-sm">
                <p class="text-gray-800 leading-relaxed">
                    {{ $order->shipping_address }}
                    @if ($order->shipping_address2)<br>{{ $order->shipping_address2 }}@endif
                    <br>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}
                    <br>{{ $order->shipping_country }}
                </p>

                @if ($order->zone)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Delivery Zone</p>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium"
                              style="background-color: {{ $order->zone->color }}20; color: {{ $order->zone->color }}">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $order->zone->color }}"></span>
                            {{ $order->zone->name }}
                        </span>
                    </div>
                @endif

                @if ($order->branch)
                    <div class="mt-3">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Branch</p>
                        <p class="text-xs text-gray-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-store text-gray-400 text-[10px]"></i>
                            {{ $order->branch->name }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        @if ($order->customer_note)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-comment-dots text-amber-600 text-sm mt-0.5"></i>
                    <div>
                        <p class="text-[10px] text-amber-700 uppercase tracking-wider font-semibold mb-1">Customer Note</p>
                        <p class="text-sm text-amber-900 leading-relaxed">{{ $order->customer_note }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Meta</h3>
            </div>
            <div class="p-5 space-y-3 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-800 uppercase">{{ $order->payment_method }}</span>
                </div>
                @if ($order->payment_gateway_id)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Gateway</span>
                        <span class="font-medium text-gray-800 font-mono text-[11px]">{{ $order->payment_gateway_id }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Source</span>
                    <span class="font-medium text-gray-800 uppercase">{{ $order->source }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if ($order->confirmed_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Confirmed</span>
                        <span class="font-medium text-gray-800">{{ $order->confirmed_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
                @if ($order->shipped_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Shipped</span>
                        <span class="font-medium text-gray-800">{{ $order->shipped_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
                @if ($order->delivered_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Delivered</span>
                        <span class="font-medium text-gray-800">{{ $order->delivered_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
                @if ($order->cancelled_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Cancelled</span>
                        <span class="font-medium text-rose-600">{{ $order->cancelled_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
function copyTrackLink(btn) {
    const link = btn.dataset.link;
    if (!link) return;

    navigator.clipboard.writeText(link).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i><span>Copied!</span>';
        btn.classList.add('bg-emerald-500', 'text-white');
        btn.classList.remove('bg-indigo-50', 'text-indigo-700', 'bg-indigo-600');

        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('bg-emerald-500', 'text-white');
        }, 1500);
    }).catch(() => {
        alert('Copy failed. Link: ' + link);
    });
}
</script>
@endpush

@endsection

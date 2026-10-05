@extends('admin.app')

@section('title', 'Technical Support')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Technical Support</h2>
        <p class="text-sm text-gray-500 mt-1">Customer support tickets and issues</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total</span>
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-headset text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
        <p class="text-[11px] text-gray-400 mt-1">All tickets</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Open</span>
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-rose-600">{{ $stats['open'] }}</div>
        <p class="text-[11px] text-gray-400 mt-1">Needs attention</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">In Progress</span>
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-gears text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-amber-600">{{ $stats['in_progress'] }}</div>
        <p class="text-[11px] text-gray-400 mt-1">Being handled</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Urgent</span>
            <div class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                <i class="fa-solid fa-fire text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-red-600">{{ $stats['urgent'] }}</div>
        <p class="text-[11px] text-gray-400 mt-1">High priority</p>
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
                   placeholder="Ticket #, subject, name, email..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                @foreach (\App\Models\SupportTicket::$statuses as $key => $meta)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Categories</option>
                @foreach (\App\Models\SupportTicket::$categories as $key => $meta)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Priorities</option>
                @foreach (\App\Models\SupportTicket::$priorities as $key => $meta)
                    <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
            </button>
            @if (request()->hasAny(['search', 'status', 'category', 'priority']))
                <a href="{{ route('admin.support.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>
</div>

@if ($tickets->count())
    <div class="space-y-3">
        @foreach ($tickets as $ticket)
            <a href="{{ route('admin.support.show', $ticket) }}"
               class="block bg-white rounded-xl border border-gray-200 hover:shadow-md hover:border-gray-300 transition overflow-hidden">

                <div class="flex items-stretch">
                    <div class="w-1.5 shrink-0
                        @if($ticket->status_color === 'rose') bg-rose-500
                        @elseif($ticket->status_color === 'amber') bg-amber-500
                        @elseif($ticket->status_color === 'blue') bg-blue-500
                        @elseif($ticket->status_color === 'emerald') bg-emerald-500
                        @else bg-gray-300 @endif"></div>

                    <div class="flex-1 p-4">
                        <div class="flex items-start gap-4">

                            <div class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0
                                @if($ticket->category_color === 'blue') bg-blue-50 text-blue-600
                                @elseif($ticket->category_color === 'emerald') bg-emerald-50 text-emerald-600
                                @elseif($ticket->category_color === 'indigo') bg-indigo-50 text-indigo-600
                                @elseif($ticket->category_color === 'purple') bg-purple-50 text-purple-600
                                @elseif($ticket->category_color === 'amber') bg-amber-50 text-amber-600
                                @elseif($ticket->category_color === 'cyan') bg-cyan-50 text-cyan-600
                                @else bg-gray-100 text-gray-600 @endif">
                                <i class="fa-solid {{ $ticket->category_icon }}"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="font-mono text-[10px] font-semibold text-indigo-600">{{ $ticket->ticket_number }}</span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">{{ $ticket->category_label }}</span>
                                    @if ($ticket->priority === 'urgent')
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-semibold">
                                            <i class="fa-solid fa-fire text-[8px]"></i> URGENT
                                        </span>
                                    @elseif ($ticket->priority === 'high')
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 font-semibold">HIGH</span>
                                    @endif
                                </div>

                                <h3 class="font-semibold text-gray-800 truncate">{{ $ticket->subject }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $ticket->description }}</p>

                                <div class="flex items-center gap-3 mt-2 text-[11px] text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-user text-[10px] text-gray-400"></i>
                                        {{ $ticket->customer_name }}
                                    </span>
                                    @if ($ticket->order_number)
                                        <span class="flex items-center gap-1 font-mono">
                                            <i class="fa-solid fa-receipt text-[10px] text-gray-400"></i>
                                            {{ $ticket->order_number }}
                                        </span>
                                    @endif
                                    @if ($ticket->attachments_count > 0)
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-paperclip text-[10px] text-gray-400"></i>
                                            {{ $ticket->attachments_count }}
                                        </span>
                                    @endif
                                    @if ($ticket->messages_count > 0)
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-comment text-[10px] text-gray-400"></i>
                                            {{ $ticket->messages_count }}
                                        </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-clock text-[10px] text-gray-400"></i>
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold
                                    @if($ticket->status_color === 'rose') bg-rose-100 text-rose-700
                                    @elseif($ticket->status_color === 'amber') bg-amber-100 text-amber-700
                                    @elseif($ticket->status_color === 'blue') bg-blue-100 text-blue-700
                                    @elseif($ticket->status_color === 'emerald') bg-emerald-100 text-emerald-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $ticket->status_label }}
                                </span>

                                @if ($ticket->assignedUser)
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-[9px] font-bold">
                                            {{ strtoupper(substr($ticket->assignedUser->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[10px] text-gray-500">{{ $ticket->assignedUser->name }}</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if ($tickets->hasPages())
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    @endif
@else
    <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
        <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 text-sm mb-1">No tickets yet</p>
        <p class="text-gray-400 text-xs">Customer issues from the app will appear here.</p>
    </div>
@endif

<style>
.line-clamp-1{display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;}
</style>

@endsection

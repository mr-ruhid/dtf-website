@extends('admin.app')

@section('title', 'Ticket ' . $ticket->ticket_number)

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('admin.support.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to tickets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800 font-mono">{{ $ticket->ticket_number }}</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold
                @if($ticket->status_color === 'rose') bg-rose-100 text-rose-700
                @elseif($ticket->status_color === 'amber') bg-amber-100 text-amber-700
                @elseif($ticket->status_color === 'blue') bg-blue-100 text-blue-700
                @elseif($ticket->status_color === 'emerald') bg-emerald-100 text-emerald-700
                @else bg-gray-100 text-gray-600 @endif">
                {{ $ticket->status_label }}
            </span>
            @if ($ticket->priority === 'urgent')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                    <i class="fa-solid fa-fire text-[9px]"></i> URGENT
                </span>
            @elseif ($ticket->priority === 'high')
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-700">HIGH</span>
            @endif
        </div>
        <p class="text-xs text-gray-400 mt-1">{{ $ticket->created_at->format('d M Y, H:i') }} · {{ $ticket->created_at->diffForHumans() }}</p>
    </div>

    <form method="POST" action="{{ route('admin.support.destroy', $ticket) }}"
          onsubmit="return confirm('Delete this ticket permanently?')">
        @csrf
        @method('DELETE')
        <button class="bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-trash text-xs"></i> Delete
        </button>
    </form>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center
                    @if($ticket->category_color === 'blue') bg-blue-50 text-blue-600
                    @elseif($ticket->category_color === 'emerald') bg-emerald-50 text-emerald-600
                    @elseif($ticket->category_color === 'indigo') bg-indigo-50 text-indigo-600
                    @elseif($ticket->category_color === 'purple') bg-purple-50 text-purple-600
                    @elseif($ticket->category_color === 'amber') bg-amber-50 text-amber-600
                    @elseif($ticket->category_color === 'cyan') bg-cyan-50 text-cyan-600
                    @else bg-gray-100 text-gray-600 @endif">
                    <i class="fa-solid {{ $ticket->category_icon }}"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $ticket->subject }}</h3>
                    <p class="text-[11px] text-gray-500">{{ $ticket->category_label }}</p>
                </div>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $ticket->description }}</p>
            </div>

            @if ($ticket->order_number || $ticket->product)
                <div class="px-6 py-3 border-t border-gray-100 bg-slate-50 flex flex-wrap items-center gap-4 text-xs">
                    @if ($ticket->order_number)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-gray-400"></i>
                            <span class="text-gray-500">Related Order:</span>
                            @if ($ticket->order)
                                <a href="{{ route('admin.orders.show', $ticket->order) }}" class="font-mono font-semibold text-indigo-600 hover:underline">
                                    {{ $ticket->order_number }}
                                </a>
                            @else
                                <span class="font-mono font-semibold text-gray-700">{{ $ticket->order_number }}</span>
                            @endif
                        </div>
                    @endif
                    @if ($ticket->product)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-cube text-gray-400"></i>
                            <span class="text-gray-500">Product:</span>
                            <span class="font-medium text-gray-700">{{ $ticket->product->name }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if ($ticket->attachments->count())
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-paperclip text-indigo-500 text-sm"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Attachments ({{ $ticket->attachments->count() }})</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($ticket->attachments as $attachment)
                            <div class="group relative rounded-lg border border-gray-200 overflow-hidden bg-slate-50">
                                @if ($attachment->is_image && $attachment->exists)
                                    <a href="{{ $attachment->file_url }}" target="_blank" class="block aspect-square">
                                        <img src="{{ $attachment->file_url }}" class="w-full h-full object-cover">
                                    </a>
                                @elseif ($attachment->exists)
                                    <a href="{{ $attachment->file_url }}" target="_blank" class="aspect-square flex flex-col items-center justify-center text-gray-400 hover:text-indigo-600 transition">
                                        <i class="fa-solid fa-file text-3xl mb-2"></i>
                                        <p class="text-[10px] text-center px-2 truncate w-full">{{ $attachment->original_name }}</p>
                                    </a>
                                @else
                                    <div class="aspect-square flex flex-col items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-triangle-exclamation text-2xl mb-1"></i>
                                        <p class="text-[10px]">Missing</p>
                                    </div>
                                @endif

                                <div class="p-2 bg-white border-t border-gray-100">
                                    <p class="text-[10px] text-gray-600 truncate">{{ $attachment->original_name }}</p>
                                    <div class="flex items-center justify-between mt-0.5">
                                        <span class="text-[9px] text-gray-400">{{ $attachment->file_size_human }}</span>
                                        @if ($attachment->is_screenshot)
                                            <span class="text-[9px] text-indigo-600 font-medium">SCREENSHOT</span>
                                        @endif
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.support.attachments.destroy', [$ticket, $attachment]) }}"
                                      onsubmit="return confirm('Delete this attachment?')"
                                      class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700">
                                        <i class="fa-solid fa-trash text-[9px]"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-comments text-indigo-500 text-sm"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Conversation ({{ $ticket->messages->count() }})</h3>
                </div>
            </div>

            <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto">

                <div class="flex gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr($ticket->customer_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-gray-800">{{ $ticket->customer_name }}</span>
                            <span class="text-[10px] text-gray-400">Customer</span>
                            <span class="text-[10px] text-gray-400 ml-auto">{{ $ticket->created_at->format('d M, H:i') }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-lg rounded-tl-none p-3">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>

                @foreach ($ticket->messages as $message)
                    @if ($message->is_from_admin)
                        <div class="flex gap-3 flex-row-reverse">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($message->sender_name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-row-reverse">
                                    <span class="text-xs font-semibold text-gray-800">{{ $message->sender_name }}</span>
                                    <span class="text-[10px] text-indigo-600 font-medium">Admin</span>
                                    <span class="text-[10px] text-gray-400 mr-auto">{{ $message->created_at->format('d M, H:i') }}</span>
                                </div>
                                <div class="bg-indigo-50 rounded-lg rounded-tr-none p-3">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($message->sender_name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold text-gray-800">{{ $message->sender_name }}</span>
                                    <span class="text-[10px] text-gray-400">Customer</span>
                                    <span class="text-[10px] text-gray-400 ml-auto">{{ $message->created_at->format('d M, H:i') }}</span>
                                </div>
                                <div class="bg-slate-50 rounded-lg rounded-tl-none p-3">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-slate-50">
                <form method="POST" action="{{ route('admin.support.reply', $ticket) }}" class="space-y-3">
                    @csrf

                    <textarea name="message" rows="3" required maxlength="5000"
                              placeholder="Type your reply to the customer..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>

                    <div class="flex items-center gap-2">
                        <select name="status"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Keep current status</option>
                            @foreach (\App\Models\SupportTicket::$statuses as $key => $meta)
                                <option value="{{ $key }}" {{ $ticket->status === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="ml-auto bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-xs"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($ticket->resolution_note)
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                    <div>
                        <p class="text-[10px] text-emerald-700 uppercase tracking-wider font-semibold mb-1">Resolution</p>
                        <p class="text-sm text-emerald-900 whitespace-pre-line">{{ $ticket->resolution_note }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="space-y-6">

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-progress text-indigo-500"></i> Update Status
            </h3>

            <form method="POST" action="{{ route('admin.support.status', $ticket) }}" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach (\App\Models\SupportTicket::$statuses as $key => $meta)
                            <option value="{{ $key }}" {{ $ticket->status === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <textarea name="note" rows="2" maxlength="1000"
                              placeholder="Resolution note (optional)..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-check text-xs mr-1"></i> Update Status
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-fire text-indigo-500"></i> Priority
            </h3>

            <form method="POST" action="{{ route('admin.support.priority', $ticket) }}">
                @csrf
                @method('PUT')

                <select name="priority" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach (\App\Models\SupportTicket::$priorities as $key => $meta)
                        <option value="{{ $key }}" {{ $ticket->priority === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-tag text-indigo-500"></i> Category
            </h3>

            <form method="POST" action="{{ route('admin.support.category', $ticket) }}">
                @csrf
                @method('PUT')

                <select name="category" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach (\App\Models\SupportTicket::$categories as $key => $meta)
                        <option value="{{ $key }}" {{ $ticket->category === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-user-tie text-indigo-500"></i> Assign To
            </h3>

            <form method="POST" action="{{ route('admin.support.assign', $ticket) }}">
                @csrf
                @method('PUT')

                <select name="assigned_to" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">— Unassigned —</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
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
                    <p class="font-medium text-gray-800">{{ $ticket->customer_name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Email</p>
                    <a href="mailto:{{ $ticket->customer_email }}" class="text-indigo-600 hover:underline break-all text-xs">{{ $ticket->customer_email }}</a>
                </div>
                @if ($ticket->customer_phone)
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Phone</p>
                        <a href="tel:{{ $ticket->customer_phone }}" class="text-indigo-600 hover:underline text-xs">{{ $ticket->customer_phone }}</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-lock text-amber-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Internal Note</h3>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('admin.support.admin-note', $ticket) }}" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <textarea name="admin_note" rows="4" maxlength="2000"
                              placeholder="Private note only admins can see..."
                              class="w-full px-3 py-2 border border-amber-200 bg-amber-50/30 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none">{{ $ticket->admin_note }}</textarea>

                    <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Save Note
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Meta</h3>
            </div>
            <div class="p-5 space-y-3 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Source</span>
                    <span class="font-medium text-gray-800 uppercase">{{ $ticket->source }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium text-gray-800">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if ($ticket->resolved_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Resolved</span>
                        <span class="font-medium text-emerald-600">{{ $ticket->resolved_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
                @if ($ticket->closed_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Closed</span>
                        <span class="font-medium text-gray-600">{{ $ticket->closed_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection

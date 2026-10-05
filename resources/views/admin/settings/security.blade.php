@extends('admin.settings.layout')

@section('title', 'Security')
@section('settings-content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Logins</span>
                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-right-to-bracket text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $logs->count() }}</div>
            <p class="text-[11px] text-gray-400 mt-1">Recent 50</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Failed</span>
                <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-rose-600">{{ $logs->where('status', 'failed')->count() }}</div>
            <p class="text-[11px] text-gray-400 mt-1">Wrong credentials</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Blocked</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-ban text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-amber-600">{{ $blockedIps->count() }}</div>
            <p class="text-[11px] text-gray-400 mt-1">Blocked IPs</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Success Rate</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
            </div>
            @php
                $successCount = $logs->where('status', 'success')->count();
                $rate = $logs->count() > 0 ? round(($successCount / $logs->count()) * 100) : 0;
            @endphp
            <div class="text-2xl font-bold text-emerald-600">{{ $rate }}%</div>
            <p class="text-[11px] text-gray-400 mt-1">{{ $successCount }} successful</p>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Login Activity</h3>
                    <p class="text-xs text-gray-500">Recent login attempts on your account</p>
                </div>
            </div>
        </div>

        @if ($logs->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Date</th>
                            <th class="px-4 py-3 text-left font-medium">Email</th>
                            <th class="px-4 py-3 text-left font-medium">Type</th>
                            <th class="px-4 py-3 text-left font-medium">Status</th>
                            <th class="px-4 py-3 text-left font-medium">IP Address</th>
                            <th class="px-4 py-3 text-left font-medium">User Agent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 text-xs truncate max-w-[180px]">{{ $log->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-slate-100 text-slate-700">
                                        {{ $log->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($log->status === 'success')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                                            <i class="fa-solid fa-check text-[8px]"></i> Success
                                        </span>
                                    @elseif ($log->status === 'blocked')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-700">
                                            <i class="fa-solid fa-ban text-[8px]"></i> Blocked
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-700">
                                            <i class="fa-solid fa-xmark text-[8px]"></i> Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $log->ip_address }}</td>
                                <td class="px-4 py-3 text-gray-500 text-[10px] truncate max-w-[200px]" title="{{ $log->user_agent }}">
                                    {{ \Illuminate\Support\Str::limit($log->user_agent, 40) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center">
                <i class="fa-solid fa-clock-rotate-left text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">No login activity yet</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-ban text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Blocked IP Addresses</h3>
                <p class="text-xs text-gray-500">Manually blocked IPs</p>
            </div>
        </div>

        <div class="p-6 space-y-4">

            <form method="POST" action="{{ route('admin.settings.security.block') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <input type="text" name="ip_address" required
                           placeholder="IP address (e.g. 192.168.1.1)"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <input type="text" name="reason"
                           placeholder="Reason (optional)"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <button type="submit"
                        class="bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-ban text-xs"></i> Block IP
                </button>
            </form>

            @if ($blockedIps->count())
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium">IP Address</th>
                                <th class="px-4 py-2 text-left font-medium">Reason</th>
                                <th class="px-4 py-2 text-left font-medium">Blocked</th>
                                <th class="px-4 py-2 text-left font-medium">Until</th>
                                <th class="px-4 py-2 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($blockedIps as $blocked)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2.5 font-mono text-xs text-gray-800">{{ $blocked->ip_address }}</td>
                                    <td class="px-4 py-2.5 text-xs text-gray-600">{{ $blocked->reason ?? '—' }}</td>
                                    <td class="px-4 py-2.5 text-xs text-gray-500">{{ $blocked->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-2.5 text-xs">
                                        @if ($blocked->blocked_until)
                                            <span class="{{ $blocked->blocked_until->isPast() ? 'text-gray-400' : 'text-amber-700 font-medium' }}">
                                                {{ $blocked->blocked_until->format('d M Y, H:i') }}
                                            </span>
                                        @else
                                            <span class="text-rose-600 font-medium">Permanent</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-right">
                                        <form method="POST" action="{{ route('admin.settings.security.unblock', $blocked) }}"
                                              onsubmit="return confirm('Unblock this IP?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs text-red-600 hover:bg-red-50 px-2.5 py-1 rounded transition">
                                                <i class="fa-solid fa-trash text-[10px] mr-1"></i> Unblock
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="border-2 border-dashed border-gray-200 rounded-lg py-8 text-center">
                    <i class="fa-solid fa-shield-halved text-2xl text-gray-300 mb-2"></i>
                    <p class="text-gray-400 text-sm">No blocked IPs</p>
                </div>
            @endif

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-user-lock text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Brute Force Protection</h3>
                <p class="text-xs text-gray-500">Auto-block after failed attempts</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Max Failed Attempts</p>
                <p class="text-2xl font-bold text-gray-800">5</p>
                <p class="text-[10px] text-gray-400 mt-1">Per email or IP</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Block Duration</p>
                <p class="text-2xl font-bold text-gray-800">6h</p>
                <p class="text-[10px] text-gray-400 mt-1">Auto unblock after</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Tracked</p>
                <p class="text-2xl font-bold text-gray-800">2FA</p>
                <p class="text-[10px] text-gray-400 mt-1">Password + 2FA codes</p>
            </div>
        </div>
    </div>

    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex gap-3">
        <i class="fa-solid fa-circle-info text-indigo-600 mt-0.5"></i>
        <div class="text-xs text-indigo-800">
            <p class="font-medium mb-1">Password & 2FA Settings</p>
            <p class="text-indigo-700">To change your password or manage 2FA, go to <a href="{{ route('admin.profile.edit') }}" class="underline font-medium">Profile Settings</a>.</p>
        </div>
    </div>

</div>

@endsection

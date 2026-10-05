@extends('admin.settings.layout')

@section('title', 'Maintenance Mode')
@section('settings-content')

@php
    $isActive = ($settings['maintenance_mode'] ?? '0') === '1';
    $startAt = $settings['maintenance_start_at'] ?? null;
    $endAt = $settings['maintenance_end_at'] ?? null;
    $autoDisable = ($settings['maintenance_auto_disable'] ?? '0') === '1';
@endphp

<form method="POST" action="{{ route('admin.settings.maintenance.update') }}" class="space-y-6">
    @csrf

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg {{ $isActive ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Maintenance Mode</h3>
                    <p class="text-xs text-gray-500">Temporarily close the site for visitors</p>
                </div>
            </div>

            @if ($isActive)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    ACTIVE
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Site is LIVE
                </span>
            @endif
        </div>

        <div class="p-6 space-y-5">

            <div class="rounded-lg border {{ $isActive ? 'bg-amber-50/60 border-amber-200' : 'bg-slate-50 border-gray-200' }} p-4 flex gap-3">
                <i class="fa-solid {{ $isActive ? 'fa-circle-exclamation text-amber-600' : 'fa-circle-info text-slate-500' }} mt-0.5"></i>
                <div class="text-sm {{ $isActive ? 'text-amber-800' : 'text-slate-600' }}">
                    <p class="font-medium mb-1">
                        {{ $isActive ? 'Your site is currently in maintenance mode' : 'Your site is live and accessible to visitors' }}
                    </p>
                    <p class="text-xs {{ $isActive ? 'text-amber-700' : 'text-slate-500' }}">
                        When enabled, visitors will see a maintenance page. The admin panel remains fully accessible.
                    </p>
                </div>
            </div>

            <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                <input type="checkbox" name="maintenance_mode" value="1"
                       {{ $isActive ? 'checked' : '' }}
                       class="rounded border-gray-300 text-amber-600 focus:ring-amber-500 w-5 h-5">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Enable Maintenance Mode</p>
                    <p class="text-xs text-gray-500">Visitors will see the maintenance page below</p>
                </div>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Message</label>
                <textarea name="maintenance_message" rows="3" maxlength="500"
                          placeholder="We'll be back soon! Our team is making improvements to serve you better."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('maintenance_message', $settings['maintenance_message'] ?? "We'll be back soon! Our team is making improvements to serve you better.") }}</textarea>
                <p class="text-xs text-gray-500 mt-1">This message will be displayed to visitors on the maintenance page</p>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-clock text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Schedule & Timer</h3>
                <p class="text-xs text-gray-500">Set when maintenance starts and ends</p>
            </div>
        </div>

        <div class="p-6 space-y-4">

            <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                <input type="checkbox" name="maintenance_auto_disable" value="1"
                       {{ $autoDisable ? 'checked' : '' }}
                       class="mt-0.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-5 h-5">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Auto-disable when end time reached</p>
                    <p class="text-xs text-gray-500">Site will automatically go live when the end time passes. No need to manually disable.</p>
                </div>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                    <input type="datetime-local" name="maintenance_start_at"
                           value="{{ old('maintenance_start_at', $startAt ? \Carbon\Carbon::parse($startAt)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">When maintenance begins (display purpose)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                    <input type="datetime-local" name="maintenance_end_at"
                           value="{{ old('maintenance_end_at', $endAt ? \Carbon\Carbon::parse($endAt)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">When maintenance ends — used for countdown</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="setDuration(1)"
                        class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-3 py-1.5 rounded-lg transition">
                    +1 hour
                </button>
                <button type="button" onclick="setDuration(6)"
                        class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-3 py-1.5 rounded-lg transition">
                    +6 hours
                </button>
                <button type="button" onclick="setDuration(24)"
                        class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-3 py-1.5 rounded-lg transition">
                    +1 day
                </button>
                <button type="button" onclick="setDuration(72)"
                        class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-3 py-1.5 rounded-lg transition">
                    +3 days
                </button>
                <button type="button" onclick="clearDuration()"
                        class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium px-3 py-1.5 rounded-lg transition ml-auto">
                    <i class="fa-solid fa-xmark text-[10px] mr-1"></i> Clear Times
                </button>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-eye text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Preview</h3>
                <p class="text-xs text-gray-500">How visitors will see your site</p>
            </div>
        </div>

        <div class="p-6">
            <div class="rounded-lg border-2 border-dashed border-gray-300 bg-slate-50 p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-screwdriver-wrench text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">Under Maintenance</h4>
                <p class="text-sm text-gray-600 max-w-md mx-auto">
                    {{ $settings['maintenance_message'] ?? "We'll be back soon! Our team is making improvements to serve you better." }}
                </p>

                @if ($endAt && \Carbon\Carbon::parse($endAt)->isFuture())
                    <div class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-gray-200">
                        <i class="fa-solid fa-clock text-amber-600 text-sm"></i>
                        <span class="text-xs text-gray-500">Back in:</span>
                        <span class="text-sm font-mono font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($endAt)->diffForHumans(null, true) }}
                        </span>
                    </div>
                @endif

                <p class="text-xs text-gray-400 mt-4">RJ SHOP &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-user-shield text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Allowed IPs (Bypass)</h3>
                <p class="text-xs text-gray-500">These IPs can access the site during maintenance</p>
            </div>
        </div>

        <div class="p-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Bypass IP Addresses</label>
            <textarea name="maintenance_allowed_ips" rows="3"
                      placeholder="One IP per line&#10;192.168.1.1&#10;10.0.0.5"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('maintenance_allowed_ips', $settings['maintenance_allowed_ips'] ?? '') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Admin panel is always accessible. Use this for testing IPs.</p>
        </div>
    </div>

    <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex gap-3">
        <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5"></i>
        <div class="text-xs text-rose-800">
            <p class="font-medium mb-1">Important</p>
            <p class="text-rose-700">Enabling maintenance mode will block all visitors except administrators. Make sure your message is clear and informative.</p>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 sticky bottom-0 bg-slate-100/80 backdrop-blur py-3 -mx-1 px-1">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20">
            <i class="fa-solid fa-floppy-disk text-xs"></i> Save Settings
        </button>
    </div>

</form>

<script>
function setDuration(hours) {
    const now = new Date();
    const end = new Date(now.getTime() + hours * 60 * 60 * 1000);

    const format = (d) => {
        const pad = (n) => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    document.querySelector('[name="maintenance_start_at"]').value = format(now);
    document.querySelector('[name="maintenance_end_at"]').value = format(end);
}

function clearDuration() {
    document.querySelector('[name="maintenance_start_at"]').value = '';
    document.querySelector('[name="maintenance_end_at"]').value = '';
}
</script>

@endsection

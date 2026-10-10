@extends('admin.app')

@section('title', 'Storage Manager')

@section('content')

<div x-data="storagePage()">

    <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Storage Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor disk usage and clean up old files</p>
        </div>
        <button type="button" @click="window.location.reload()"
                class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-rotate text-xs"></i>
            <span>Refresh</span>
        </button>
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

    {{-- ============ OVERVIEW CARDS ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-5 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-white/80">Total Used</span>
                <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-database text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold">{{ $overview['total_size_human'] }}</div>
            <p class="text-[11px] text-white/80 mt-1">{{ number_format($overview['total_files']) }} files</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Disk Free</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-hard-drive text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-emerald-600">
                @if ($overview['disk_free'] !== null)
                    {{ human_size($overview['disk_free']) }}
                @else
                    N/A
                @endif
            </div>
            <p class="text-[11px] text-gray-400 mt-1">
                @if ($overview['disk_total'])
                    of {{ human_size($overview['disk_total']) }} total
                @else
                    disk info unavailable
                @endif
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Orphan Files</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-ghost text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-amber-600">{{ count($orphans) }}</div>
            <p class="text-[11px] text-gray-400 mt-1">
                @php
                    $orphanTotal = collect($orphans)->sum('size');
                @endphp
                {{ human_size($orphanTotal) }} recoverable
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Folders</span>
                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-folder-tree text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ count($overview['folders']) }}</div>
            <p class="text-[11px] text-gray-400 mt-1">tracked directories</p>
        </div>

    </div>

    {{-- ============ QUICK CLEANUP ACTIONS ============ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- Temp Cleanup --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-xs"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Temp Cleanup</h3>
                    <p class="text-[11px] text-gray-400">Remove old temp uploads and zips</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.storage.clean-tmp') }}"
                  onsubmit="return confirm('Delete temp files older than selected age?')"
                  class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Age</label>
                    <select name="hours"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="6">Older than 6 hours</option>
                        <option value="24" selected>Older than 24 hours</option>
                        <option value="72">Older than 3 days</option>
                        <option value="168">Older than 7 days</option>
                    </select>
                </div>
                <button type="submit"
                        class="w-full bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-broom text-xs"></i>
                    <span>Clean Temp Files</span>
                </button>
            </form>
        </div>

        {{-- Order Designs Cleanup --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-file-image text-xs"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Old Order Artwork</h3>
                    <p class="text-[11px] text-gray-400">Delivered orders artwork cleanup</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.storage.clean-orders') }}"
                  onsubmit="return confirm('Delete old order artwork files? This cannot be undone.')"
                  class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Age</label>
                    <select name="days"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="90">Older than 90 days</option>
                        <option value="180" selected>Older than 180 days</option>
                        <option value="365">Older than 1 year</option>
                    </select>
                </div>
                <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-broom text-xs"></i>
                    <span>Clean Old Artwork</span>
                </button>
                <p class="text-[10px] text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1.5">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    Active orders (pending, confirmed, processing) are never touched
                </p>
            </form>
        </div>

        {{-- Orphans Cleanup --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-ghost text-xs"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Orphan Files</h3>
                    <p class="text-[11px] text-gray-400">Files without DB reference</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between">
                    <span class="text-xs text-gray-600">Found</span>
                    <span class="text-sm font-bold text-gray-800">{{ count($orphans) }} files</span>
                </div>
                <a href="#orphans-section"
                   class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-down text-xs"></i>
                    <span>Review Orphans</span>
                </a>
            </div>
        </div>

    </div>

    {{-- ============ FOLDER BREAKDOWN ============ --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-folder-tree text-indigo-500 text-sm"></i>
            <h3 class="font-semibold text-gray-800 text-sm">Folder Breakdown</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Folder</th>
                        <th class="px-6 py-3 text-left font-medium">Path</th>
                        <th class="px-6 py-3 text-right font-medium">Files</th>
                        <th class="px-6 py-3 text-right font-medium">Size</th>
                        <th class="px-6 py-3 text-left font-medium w-[200px]">Share</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($overview['folders'] as $folder)
                        @php
                            $pct = $overview['total_size'] > 0 ? ($folder['size'] / $overview['total_size']) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $folder['icon'] }} text-[11px]"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $folder['label'] }}</span>
                                    @if ($folder['protected'])
                                        <span class="text-[9px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-semibold uppercase tracking-wider">protected</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <code class="text-[11px] font-mono text-gray-500">{{ $folder['path'] }}</code>
                            </td>
                            <td class="px-6 py-3 text-right text-gray-700 font-mono text-xs">
                                {{ number_format($folder['files']) }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-gray-800">
                                {{ $folder['size_human'] }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"
                                             style="width: {{ min(100, $pct) }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-gray-500 font-mono w-12 text-right">{{ number_format($pct, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============ LARGEST + RECENT ============ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Largest Files --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-ranking-star text-rose-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Largest Files</h3>
            </div>

            @if (count($largest))
                <div class="divide-y divide-gray-100 max-h-[420px] overflow-y-auto">
                    @foreach ($largest as $file)
                        <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50 group">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file text-[11px]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-800 truncate" title="{{ $file['path'] }}">
                                    {{ $file['name'] }}
                                </p>
                                <p class="text-[10px] text-gray-400 truncate">
                                    {{ $file['folder_label'] }} · {{ date('d M Y', $file['modified_at']) }}
                                </p>
                            </div>
                            <div class="text-right shrink-0 flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-700 font-mono">{{ $file['size_human'] }}</span>
                                <button type="button"
                                        @click="deleteFile('{{ $file['path'] }}', $event)"
                                        class="w-7 h-7 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition"
                                        title="Delete file">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-gray-400 text-sm">
                    <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                    No files found
                </div>
            @endif
        </div>

        {{-- Recent Files --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-clock text-emerald-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Recently Added (24h)</h3>
            </div>

            @if (count($recent))
                <div class="divide-y divide-gray-100 max-h-[420px] overflow-y-auto">
                    @foreach ($recent as $file)
                        <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50 group">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-arrow-up text-[11px]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-800 truncate" title="{{ $file['path'] }}">
                                    {{ $file['name'] }}
                                </p>
                                <p class="text-[10px] text-gray-400 truncate">
                                    {{ $file['folder_label'] }} · {{ \Carbon\Carbon::createFromTimestamp($file['modified_at'])->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right shrink-0 flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-700 font-mono">{{ $file['size_human'] }}</span>
                                <button type="button"
                                        @click="deleteFile('{{ $file['path'] }}', $event)"
                                        class="w-7 h-7 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition"
                                        title="Delete file">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-gray-400 text-sm">
                    <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                    No new files in the last 24 hours
                </div>
            @endif
        </div>

    </div>

    {{-- ============ ORPHANS SECTION ============ --}}
    <div id="orphans-section" class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-ghost text-amber-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Orphan Files</h3>
                <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded font-bold uppercase tracking-wider">{{ count($orphans) }}</span>
            </div>
            @if (count($orphans))
                <form method="POST" action="{{ route('admin.storage.delete-orphans') }}"
                      onsubmit="return confirm('Delete all orphan files permanently?')">
                    @csrf
                    @foreach ($orphans as $orphan)
                        <input type="hidden" name="paths[]" value="{{ $orphan['path'] }}">
                    @endforeach
                    <button type="submit"
                            class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-trash text-[10px]"></i>
                        <span>Delete all orphans</span>
                    </button>
                </form>
            @endif
        </div>

        <div class="px-6 py-3 bg-amber-50 border-b border-amber-200">
            <p class="text-[11px] text-amber-800 leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Files on disk without a database reference. These may be leftover from deleted products, orders, or uploaded accidentally. <strong>Always verify before deleting</strong> — some files may still be in use.
            </p>
        </div>

        @if (count($orphans))
            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                @foreach ($orphans as $orphan)
                    <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50 group">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-file text-[12px]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-800 truncate" title="{{ $orphan['path'] }}">
                                {{ $orphan['name'] }}
                            </p>
                            <p class="text-[10px] text-gray-400 truncate">
                                <code class="font-mono">{{ $orphan['path'] }}</code>
                                · {{ date('d M Y', $orphan['modified_at']) }}
                            </p>
                        </div>
                        <div class="text-right shrink-0 flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-700 font-mono">{{ $orphan['size_human'] }}</span>
                            <button type="button"
                                    @click="deleteFile('{{ $orphan['path'] }}', $event)"
                                    class="w-7 h-7 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 transition"
                                    title="Delete this file">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center text-gray-400 text-sm">
                <i class="fa-solid fa-circle-check text-2xl text-emerald-400 mb-2 block"></i>
                <p class="text-emerald-600 font-medium">No orphan files found</p>
                <p class="text-[11px] mt-1">Everything on disk is properly referenced in the database</p>
            </div>
        @endif
    </div>

    {{-- ============ CLEANUP LOG ============ --}}
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-800 text-sm mb-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-slate-500"></i>
            How it works
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-600">
            <div>
                <p class="font-semibold text-gray-800 mb-1">Temp files</p>
                <p>Shopping session files and temporary zips are automatically deleted after their age threshold. Safe to clean at any time.</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Order artwork</p>
                <p>Only delivered/cancelled/refunded orders older than the selected age. Orders still in production are always preserved.</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Orphans</p>
                <p>Files on disk that no database record points to. Review the list before bulk deletion — verify nothing important is there.</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function storagePage() {
    return {
        async deleteFile(path, event) {
            if (!confirm('Delete this file permanently?\n\n' + path)) return;

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

            try {
                const res = await fetch('{{ route('admin.storage.delete-file') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ path })
                });

                const data = await res.json();

                if (data.success) {
                    const row = event.target.closest('.group');
                    if (row) {
                        row.style.transition = 'all 0.3s';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => row.remove(), 300);
                    }
                } else {
                    alert(data.message || 'Delete failed');
                }
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }
    };
}
</script>
@endpush

@endsection
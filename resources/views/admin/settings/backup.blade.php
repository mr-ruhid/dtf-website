@extends('admin.settings.layout')

@section('title', 'Backup')
@section('settings-content')

<div class="space-y-6" x-data="{
    showRestore: false,
    restoreFile: '',
    selectedType: 'full'
}">

    <div class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-1">Backup Management</h3>
                <p class="text-sm text-teal-100">Create, download and restore your site backups</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-database text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-4 text-xs">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-folder-open"></i>
                <span>{{ count($backups) }} backup{{ count($backups) !== 1 ? 's' : '' }} available</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-plus text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Create New Backup</h3>
                <p class="text-xs text-gray-500">Choose what to include in the backup</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.backup.create') }}" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <label class="cursor-pointer">
                    <input type="radio" name="type" value="database" x-model="selectedType" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-database"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Database Only</p>
                        <p class="text-xs text-gray-500">Products, orders, users and all data</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="type" value="files" x-model="selectedType" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-folder-tree"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Files Only</p>
                        <p class="text-xs text-gray-500">App code, views, config and routes</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="type" value="full" x-model="selectedType" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition relative">
                        <span class="absolute top-2 right-2 text-[9px] bg-emerald-100 text-emerald-700 font-bold px-1.5 py-0.5 rounded">RECOMMENDED</span>
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Full Backup</p>
                        <p class="text-xs text-gray-500">Database + Files combined</p>
                    </div>
                </label>

            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Backup Name (optional)</label>
                <input type="text" name="name" maxlength="100"
                       placeholder="e.g. before-update, weekly-backup"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Letters, numbers, hyphens and underscores only</p>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                    <i class="fa-solid fa-cloud-arrow-up text-xs"></i> Create Backup
                </button>
            </div>

        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Backup History</h3>
                    <p class="text-xs text-gray-500">{{ count($backups) }} backup{{ count($backups) !== 1 ? 's' : '' }} found</p>
                </div>
            </div>
        </div>

        @if (count($backups) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Type</th>
                            <th class="px-4 py-3 text-left font-medium">File Name</th>
                            <th class="px-4 py-3 text-left font-medium">Size</th>
                            <th class="px-4 py-3 text-left font-medium">Created</th>
                            <th class="px-4 py-3 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($backups as $backup)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    @if ($backup['type'] === 'database')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700">
                                            <i class="fa-solid fa-database text-[9px]"></i> DATABASE
                                        </span>
                                    @elseif ($backup['type'] === 'files')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-700">
                                            <i class="fa-solid fa-folder text-[9px]"></i> FILES
                                        </span>
                                    @elseif ($backup['type'] === 'full')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                                            <i class="fa-solid fa-layer-group text-[9px]"></i> FULL
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600">
                                            UNKNOWN
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-mono text-xs text-gray-800 truncate max-w-xs">{{ $backup['name'] }}</p>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 font-mono">{{ $backup['size_human'] }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $backup['created_at'] }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.settings.backup.download', $backup['name']) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition" title="Download">
                                            <i class="fa-solid fa-download text-xs"></i>
                                        </a>

                                        <button @click="showRestore = true; restoreFile = '{{ $backup['name'] }}'"
                                                class="w-8 h-8 flex items-center justify-center rounded hover:bg-amber-50 text-amber-600 transition" title="Restore">
                                            <i class="fa-solid fa-rotate-left text-xs"></i>
                                        </button>

                                        <form method="POST" action="{{ route('admin.settings.backup.destroy', $backup['name']) }}"
                                              onsubmit="return confirm('Delete this backup? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition" title="Delete">
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
        @else
            <div class="py-16 text-center">
                <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm mb-1">No backups yet</p>
                <p class="text-gray-400 text-xs">Create your first backup above.</p>
            </div>
        @endif
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
        <div class="text-xs text-amber-800">
            <p class="font-medium mb-1">Restore Warning</p>
            <p class="text-amber-700">Restoring a backup will overwrite your current data and/or files. Make sure you create a fresh backup before restoring.</p>
        </div>
    </div>

    <div x-show="showRestore" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showRestore = false">
        <div class="bg-white rounded-xl w-full max-w-md">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Restore Backup</h3>
                        <p class="text-[11px] text-gray-500">This will overwrite current data</p>
                    </div>
                </div>
                <button @click="showRestore = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="`/admin/settings/backup/restore/${restoreFile}`" class="p-6 space-y-4">
                @csrf

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-xs text-amber-800 mb-2">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> You are about to restore:
                    </p>
                    <p class="text-xs font-mono font-semibold text-amber-900 break-all" x-text="restoreFile"></p>
                </div>

                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 space-y-2">
                    <p class="text-xs font-semibold text-rose-800 mb-2">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> This action will:
                    </p>
                    <ul class="text-xs text-rose-700 space-y-1 list-disc list-inside">
                        <li>Overwrite your current database (if included)</li>
                        <li>Overwrite your current files (if included)</li>
                        <li>Clear all caches</li>
                        <li>Cannot be undone</li>
                    </ul>
                </div>

                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                    <input type="checkbox" name="confirm" value="1" required
                           class="mt-0.5 rounded border-gray-300 text-rose-600 focus:ring-rose-500 w-4 h-4">
                    <span class="text-sm text-gray-700">
                        I understand this will overwrite current data. I have created a fresh backup.
                    </span>
                </label>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <i class="fa-solid fa-rotate-left text-xs mr-1"></i> Restore Now
                    </button>
                    <button type="button" @click="showRestore = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

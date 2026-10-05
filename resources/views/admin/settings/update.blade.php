@extends('admin.settings.layout')

@section('title', 'System Update')
@section('settings-content')

<div x-data="{
    uploading: false,
    progress: 0,
    result: null
}">

    <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-1">System Update</h3>
                <p class="text-sm text-purple-100">Upload an update package to install new features and fixes</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-4 text-xs">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-white/20 font-mono">Current</span>
                <span class="font-mono">RJ CMS Lite 1.2</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-file-zipper text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Upload Update Package</h3>
                <p class="text-xs text-gray-500">Select a .zip file from your computer</p>
            </div>
        </div>

        <div class="p-6 space-y-4">

            <form id="update-form" method="POST" action="{{ route('admin.settings.update.install') }}" enctype="multipart/form-data">
                @csrf

                <div class="border-2 border-dashed border-gray-300 hover:border-indigo-400 rounded-xl p-8 text-center transition cursor-pointer"
                     onclick="document.getElementById('update_file').click()">
                    <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-800 mb-1">Click to select ZIP file</p>
                    <p class="text-xs text-gray-500">Maximum file size: 100MB</p>
                    <input type="file" id="update_file" name="update_file" accept=".zip" class="hidden" required
                           onchange="handleFileSelect(this)">
                </div>

                <div id="file-info" class="hidden mt-4 p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-zipper"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate" id="file-name">—</p>
                        <p class="text-xs text-gray-500" id="file-size">—</p>
                    </div>
                    <button type="button" onclick="clearFile()" class="text-red-600 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-lg transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
                    <button type="submit" id="submit-btn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                            onclick="return confirmUpdate()">
                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i> Install Update
                    </button>
                    <a href="{{ route('admin.settings.index') }}"
                       class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</a>
                </div>

            </form>

            <div id="loading" class="hidden mt-4">
                <div class="bg-slate-50 rounded-lg p-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-spinner fa-spin text-indigo-600"></i>
                        <span class="text-sm font-medium text-gray-800">Installing update...</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-full rounded-full animate-pulse" style="width: 100%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Please wait, do not close this page.</p>
                </div>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">What Gets Updated</h3>
                <p class="text-xs text-gray-500">Protected and updateable areas</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <div class="flex items-center gap-2 mb-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <h4 class="font-medium text-gray-800 text-sm">Will be Updated</h4>
                </div>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <code class="font-mono text-gray-800">app/</code> — Controllers, Models
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <code class="font-mono text-gray-800">routes/</code> — Route files
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <code class="font-mono text-gray-800">resources/</code> — Views, assets
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <code class="font-mono text-gray-800">config/</code> — Config files
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <code class="font-mono text-gray-800">database/</code> — Migrations, seeders
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <code class="font-mono text-gray-800">public/</code> — Assets (except uploads)
                    </li>
                </ul>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-3">
                    <i class="fa-solid fa-lock text-amber-600 text-sm"></i>
                    <h4 class="font-medium text-gray-800 text-sm">Protected (Kept)</h4>
                </div>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <code class="font-mono text-gray-800">.env</code> — Your credentials
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <code class="font-mono text-gray-800">storage/</code> — Uploaded files
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <code class="font-mono text-gray-800">public/storage/</code> — Symlinks
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <code class="font-mono text-gray-800">vendor/</code> — Dependencies
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <code class="font-mono text-gray-800">database/database.sqlite</code> — Your data
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Before You Update</h3>
                <p class="text-xs text-gray-500">Important reminders</p>
            </div>
        </div>

        <div class="p-6 space-y-3 text-xs text-gray-700">
            <div class="flex gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                <p><strong>Automatic backup:</strong> Old code will be backed up to <code class="bg-gray-100 px-1 rounded font-mono">storage/app/backups/</code> before update.</p>
            </div>
            <div class="flex gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                <p><strong>Database:</strong> New migrations will run automatically. Your existing data is safe.</p>
            </div>
            <div class="flex gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                <p><strong>Cache:</strong> All caches will be cleared and regenerated.</p>
            </div>
            <div class="flex gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
                <p><strong>Backup recommendation:</strong> Download a full backup from <a href="{{ route('admin.settings.backup') }}" class="text-indigo-600 underline font-medium">Backup Settings</a> before updating.</p>
            </div>
        </div>
    </div>

</div>

<script>
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;

    if (!file.name.endsWith('.zip')) {
        alert('Only .zip files are allowed');
        input.value = '';
        return;
    }

    document.getElementById('file-info').classList.remove('hidden');
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = formatSize(file.size);
}

function clearFile() {
    document.getElementById('update_file').value = '';
    document.getElementById('file-info').classList.add('hidden');
}

function formatSize(bytes) {
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = bytes;
    let i = 0;
    while (size >= 1024 && i < units.length - 1) {
        size /= 1024;
        i++;
    }
    return size.toFixed(2) + ' ' + units[i];
}

function confirmUpdate() {
    const file = document.getElementById('update_file').files[0];
    if (!file) {
        alert('Please select an update file');
        return false;
    }

    if (!confirm('Are you sure you want to install this update?\n\nA backup will be created automatically. Continue?')) {
        return false;
    }

    document.getElementById('submit-btn').disabled = true;
    document.getElementById('loading').classList.remove('hidden');
    return true;
}
</script>

@endsection

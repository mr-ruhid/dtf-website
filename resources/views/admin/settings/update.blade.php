@extends('admin.settings.layout')

@section('title', 'System Update')
@section('settings-content')

<div x-data="updateManager()" x-init="init()">

    <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl p-6 text-white mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-1">System Update</h3>
                <p class="text-sm text-purple-100">Check for new features and fixes directly from GitHub</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-cloud-arrow-down text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-4 text-xs">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-white/20 font-mono">Current Version</span>
                <span class="font-mono" x-text="currentVersion"></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-brands fa-github text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">GitHub Releases</h3>
                    <p class="text-xs text-gray-500">Updates are pulled directly from the official repository</p>
                </div>
            </div>

            <button @click="checkUpdate()" :disabled="checking"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition flex items-center gap-2 disabled:opacity-50">
                <i class="fa-solid" :class="checking ? 'fa-spinner fa-spin' : 'fa-rotate'" ></i>
                <span x-text="checking ? 'Checking...' : 'Check Again'"></span>
            </button>
        </div>

        <div class="p-6">

            <!-- Error State -->
            <div x-show="error" class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm mb-4">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> <span x-text="error"></span>
            </div>

            <!-- Loading State -->
            <div x-show="checking" class="flex flex-col items-center justify-center py-8">
                <i class="fa-solid fa-spinner fa-spin text-3xl text-indigo-500 mb-3"></i>
                <p class="text-sm text-gray-500">Checking GitHub for latest release...</p>
            </div>

            <!-- Up to date -->
            <div x-show="!checking && !error && !updateAvailable" class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-1">You are up to date!</h4>
                <p class="text-sm text-gray-500">You are running the latest version of RJ SHOP Lite.</p>
            </div>

            <!-- Update Available -->
            <div x-show="!checking && !error && updateAvailable" x-cloak>
                <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg mb-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-arrow-up text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-indigo-900">New Update Available!</h4>
                        <p class="text-xs text-indigo-700 mt-1">Version <strong x-text="latestVersion"></strong> is ready to install. You are currently on <span x-text="currentVersion"></span>.</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Release Notes</h5>
                    <div class="bg-slate-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-wrap font-mono text-xs max-h-64 overflow-y-auto scroll-thin" x-text="releaseNotes"></div>
                </div>

                <form id="install-form" method="POST" action="{{ route('admin.settings.update.install') }}">
                    @csrf
                    <input type="hidden" name="download_url" :value="downloadUrl">

                    <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
                        <button type="button" @click="installUpdate()" :disabled="installing"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20 disabled:opacity-50">
                            <i class="fa-solid" :class="installing ? 'fa-spinner fa-spin' : 'fa-cloud-arrow-down'"></i>
                            <span x-text="installing ? 'Installing... Please wait' : 'Install Update'"></span>
                        </button>
                        <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Secure Update Process</h3>
                <p class="text-xs text-gray-500">What happens under the hood</p>
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

</div>

<script>
function updateManager() {
    return {
        checking: true,
        installing: false,
        updateAvailable: false,
        currentVersion: '1.1.2',
        latestVersion: '1.1.2',
        releaseNotes: '',
        downloadUrl: '',
        error: null,

        init() {
            this.checkUpdate();
        },

        async checkUpdate() {
            this.checking = true;
            this.error = null;
            try {
                const response = await fetch('{{ route('admin.settings.update.check') }}');
                const data = await response.json();

                if (data.error) {
                    this.error = data.error;
                } else {
                    this.currentVersion = data.current_version;
                    this.latestVersion = data.latest_version;
                    this.updateAvailable = data.is_update_available;
                    this.releaseNotes = data.release_notes || 'No release notes provided.';
                    this.downloadUrl = data.download_url;
                }
            } catch (e) {
                this.error = 'Failed to connect to GitHub API. Please check your internet connection or API rate limits.';
            } finally {
                this.checking = false;
            }
        },

        installUpdate() {
            if (!confirm('Are you sure you want to install this update?\n\nA backup of your current code will be created automatically before proceeding. Continue?')) {
                return;
            }
            this.installing = true;
            document.getElementById('install-form').submit();
        }
    }
}
</script>

@endsection

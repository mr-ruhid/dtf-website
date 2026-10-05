@extends('admin.settings.layout')

@section('title', 'Contact & Social')
@section('settings-content')

<form method="POST" action="{{ route('admin.settings.contact.update') }}" class="space-y-6">
    @csrf

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-address-book text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Contact Information</h3>
                <p class="text-xs text-gray-500">How customers can reach you</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}"
                           placeholder="info@example.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings['site_phone'] ?? '') }}"
                           placeholder="+1 (555) 000-0000"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="site_address" value="{{ old('site_address', $settings['site_address'] ?? '') }}"
                       placeholder="123 Main Street, Los Angeles, CA 90001"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                <i class="fa-solid fa-share-nodes text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Social Media</h3>
                <p class="text-xs text-gray-500">Connect your social profiles</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ([
                'facebook_url' => ['Facebook', 'fa-facebook', 'https://facebook.com/yourpage', 'text-blue-600'],
                'instagram_url' => ['Instagram', 'fa-instagram', 'https://instagram.com/yourpage', 'text-pink-600'],
                'twitter_url' => ['Twitter / X', 'fa-x-twitter', 'https://x.com/yourpage', 'text-slate-800'],
                'youtube_url' => ['YouTube', 'fa-youtube', 'https://youtube.com/@yourchannel', 'text-red-600'],
                'linkedin_url' => ['LinkedIn', 'fa-linkedin', 'https://linkedin.com/company/yourpage', 'text-blue-700'],
                'tiktok_url' => ['TikTok', 'fa-tiktok', 'https://tiktok.com/@yourpage', 'text-slate-900'],
            ] as $key => $meta)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa-brands {{ $meta[1] }} mr-1 {{ $meta[3] }}"></i> {{ $meta[0] }}
                    </label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}"
                           placeholder="{{ $meta[2] }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 sticky bottom-0 bg-slate-100/80 backdrop-blur py-3 -mx-1 px-1">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20">
            <i class="fa-solid fa-floppy-disk text-xs"></i> Save Settings
        </button>
    </div>

</form>

@endsection

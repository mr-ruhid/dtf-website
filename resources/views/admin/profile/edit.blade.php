@extends('admin.app')

@section('title', 'Profile')

@section('content')

<div x-data="{ tab: '{{ session('tab', 'profile') }}' }">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">My Profile</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your account settings and security</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">

        <div class="border-b border-gray-200 px-6">
            <nav class="flex gap-6 -mb-px">
                <button @click="tab = 'profile'"
                        :class="tab === 'profile' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Profile
                </button>
                <button @click="tab = 'password'"
                        :class="tab === 'password' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Password
                </button>
                <button @click="tab = 'security'"
                        :class="tab === 'security' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Security
                </button>
            </nav>
        </div>

        <div class="p-6">

            <div x-show="tab === 'profile'">
                <form method="POST" action="{{ route('admin.profile.update') }}" class="max-w-lg space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        Save Changes
                    </button>
                </form>
            </div>

            <div x-show="tab === 'password'" x-cloak>
                <form method="POST" action="{{ route('admin.profile.password') }}" class="max-w-lg space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        Update Password
                    </button>
                </form>
            </div>

            <div x-show="tab === 'security'" x-cloak class="max-w-2xl">

                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-medium text-gray-800 text-sm">Two-Factor Authentication</h3>
                            @if ($user->two_factor_enabled)
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">Active</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">Disabled</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Adds an extra layer of security by sending a code to your email on every login.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.two-factor') }}" class="mt-4 max-w-sm space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm with your password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="{{ $user->two_factor_enabled ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        {{ $user->two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA' }}
                    </button>
                </form>

                <div class="mt-8">
                    <h3 class="font-medium text-gray-800 text-sm mb-3">Recent Login Activity</h3>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium">Date</th>
                                    <th class="px-4 py-2 text-left font-medium">Type</th>
                                    <th class="px-4 py-2 text-left font-medium">Status</th>
                                    <th class="px-4 py-2 text-left font-medium">IP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse (\App\Models\LoginLog::where('email', $user->email)->latest()->limit(10)->get() as $log)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-600">{{ $log->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-2 text-gray-600 uppercase text-xs">{{ $log->type }}</td>
                                        <td class="px-4 py-2">
                                            @if ($log->status === 'success')
                                                <span class="px-2 py-0.5 rounded text-xs bg-emerald-100 text-emerald-700">Success</span>
                                            @elseif ($log->status === 'blocked')
                                                <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-700">Blocked</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-700">Failed</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-gray-500 font-mono text-xs">{{ $log->ip_address }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-400 text-sm">No activity yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

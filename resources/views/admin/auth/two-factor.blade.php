<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Verification - RJ SHOP lite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-white font-bold text-3xl">RJ SHOP <span class="text-indigo-400">lite</span></h1>
            <p class="text-slate-400 text-sm mt-2">Two-Factor Authentication</p>
        </div>

        <div class="bg-white rounded-xl shadow-2xl p-8">

            <div class="w-14 h-14 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>

            <h2 class="text-xl font-semibold text-gray-800 text-center mb-1">Verify your identity</h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                We sent a 6-digit code to <span class="font-medium text-gray-700">{{ $masked }}</span>
            </p>

            @if (session('status'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.2fa.verify') }}" class="space-y-4" x-data="{ code: '' }">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                    <input type="text"
                           name="code"
                           x-model="code"
                           @input="code = code.replace(/\D/g, '').slice(0, 6)"
                           inputmode="numeric"
                           autocomplete="one-time-code"
                           maxlength="6"
                           required
                           autofocus
                           placeholder="000000"
                           class="w-full text-center text-2xl tracking-[0.5em] font-semibold px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <button type="submit"
                        :disabled="code.length !== 6"
                        :class="code.length === 6 ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full text-white font-medium py-2.5 rounded-lg transition text-sm">
                    Verify
                </button>
            </form>

            <form method="POST" action="{{ route('admin.2fa.resend') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full text-sm text-indigo-600 hover:underline">
                    Didn't receive the code? Resend
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <a href="{{ route('admin.login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to login
                </a>
            </div>

        </div>

        <p class="text-center text-slate-500 text-xs mt-6">&copy; {{ date('Y') }} RJ SHOP lite. All rights reserved.</p>

    </div>

</body>
</html>

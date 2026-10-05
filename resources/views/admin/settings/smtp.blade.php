@extends('admin.settings.layout')

@section('title', 'SMTP Settings')
@section('settings-content')

<div x-data="{ testing: false, testResult: null }">

    <form method="POST" action="{{ route('admin.settings.smtp.update') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">SMTP Configuration</h3>
                    <p class="text-xs text-gray-500">Mail server settings for outgoing emails</p>
                </div>
            </div>

            <div class="p-6 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Mailer</label>
                        <select name="mail_mailer" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach (['smtp' => 'SMTP', 'log' => 'Log (Testing)', 'sendmail' => 'Sendmail', 'mailgun' => 'Mailgun', 'ses' => 'Amazon SES', 'postmark' => 'Postmark'] as $key => $label)
                                <option value="{{ $key }}" {{ ($settings['mail_mailer'] ?? 'smtp') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                        <select name="mail_encryption"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ ($settings['mail_encryption'] ?? '') === '' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}"
                               placeholder="smtp.gmail.com"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                        <input type="text" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}"
                               placeholder="587"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Username</label>
                        <input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                               placeholder="your-email@gmail.com"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Password</label>
                        <input type="password" name="mail_password" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}"
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                               placeholder="noreply@example.com"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                               placeholder="RJ SHOP"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Test Email</h3>
                    <p class="text-xs text-gray-500">Send a test email to verify configuration</p>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Email</label>
                        <input type="email" id="test_email" placeholder="test@example.com"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <button type="button"
                            @click="
                                const email = document.getElementById('test_email').value;
                                if (!email) { alert('Please enter an email address'); return; }
                                testing = true; testResult = null;
                                fetch('{{ route('admin.settings.smtp.test') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ email })
                                })
                                .then(r => r.json())
                                .then(data => { testing = false; testResult = data; })
                                .catch(e => { testing = false; testResult = { success: false, message: 'Network error' }; })
                            "
                            :disabled="testing"
                            class="bg-slate-700 hover:bg-slate-800 disabled:opacity-50 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs" x-show="!testing"></i>
                        <i class="fa-solid fa-spinner fa-spin text-xs" x-show="testing"></i>
                        <span x-text="testing ? 'Sending...' : 'Send Test'"></span>
                    </button>
                </div>

                <div x-show="testResult" x-transition
                     :class="testResult?.success ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800'"
                     class="border rounded-lg p-4 flex gap-3">
                    <i class="fa-solid text-sm mt-0.5"
                       :class="testResult?.success ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-red-600'"></i>
                    <p class="text-sm" x-text="testResult?.message"></p>
                </div>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
            <i class="fa-solid fa-lightbulb text-amber-600 mt-0.5"></i>
            <div class="text-xs text-amber-800">
                <p class="font-medium mb-1">Common SMTP Settings</p>
                <ul class="space-y-0.5 list-disc list-inside text-amber-700">
                    <li><strong>Gmail:</strong> smtp.gmail.com · Port 587 · TLS · Use App Password</li>
                    <li><strong>Outlook:</strong> smtp.office365.com · Port 587 · TLS</li>
                    <li><strong>Mailgun:</strong> smtp.mailgun.org · Port 587 · TLS</li>
                </ul>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 sticky bottom-0 bg-slate-100/80 backdrop-blur py-3 -mx-1 px-1">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Save Settings
            </button>
        </div>

    </form>

</div>

@endsection

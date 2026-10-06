@extends('admin.app')

@section('title', 'Services')

@section('content')

<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
            <i class="fa-solid fa-rocket text-lg"></i>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Extra Services</h2>
            <p class="text-sm text-gray-500 mt-0.5">Enhance your store with powerful add-ons</p>
        </div>
    </div>
</div>

<div class="mb-6 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-6 md:p-8 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 25%, rgba(255,255,255,0.4), transparent 40%), radial-gradient(circle at 85% 75%, rgba(255,255,255,0.3), transparent 40%);"></div>

    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="flex-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-xs font-medium mb-4">
                <span class="w-1.5 h-1.5 bg-emerald-300 rounded-full animate-pulse"></span>
                <span>Available Add-ons</span>
            </div>
            <h3 class="text-2xl md:text-3xl font-bold mb-2">Power up your store</h3>
            <p class="text-white/80 text-sm max-w-xl leading-relaxed">
                Ready-to-deploy services that help you grow faster — notifications, plugins, and integrations. Custom pricing per store.
            </p>
        </div>

        <div class="flex flex-col gap-2">
            <a href="https://wa.me/994506636031" target="_blank"
               class="inline-flex items-center justify-center gap-2 bg-white text-indigo-600 hover:bg-indigo-50 font-semibold text-sm px-5 py-3 rounded-xl transition-all shadow-lg">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span>Talk to us</span>
            </a>
            <span class="text-center text-[11px] text-white/70 font-mono">Reply within minutes</span>
        </div>
    </div>
</div>

<div class="mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="font-mono text-[10px] uppercase tracking-[0.3em] text-indigo-500">// Notifications</div>
        <div class="flex-1 h-px bg-gradient-to-r from-indigo-200 to-transparent"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-comment-sms text-lg"></i>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200 text-[10px] font-mono uppercase tracking-wider text-amber-700">Popular</span>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">SMS Notifications</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                Send order status, delivery updates, and product information directly to your customer's phone.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Order status SMS</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Delivery notifications</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Custom triggers</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="https://wa.me/994506636031?text=Hi!%20I%20am%20interested%20in%20SMS%20Notifications" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-green-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-brands fa-whatsapp text-xl"></i>
                </div>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">WhatsApp Notifications</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                Automatically send order updates and product info via WhatsApp — no app switch needed.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Order + payment confirmations</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Two-way chat support</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Broadcast messages</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="https://wa.me/994506636031?text=Hi!%20I%20am%20interested%20in%20WhatsApp%20Notifications" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-brands fa-telegram text-xl"></i>
                </div>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Telegram Bot</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                A dedicated Telegram bot that pushes every order update to you and your team in real time.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Real-time order feed</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Admin group alerts</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Inline approve / reject</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="https://t.me/veb_developher_az" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-envelope text-lg"></i>
                </div>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Email Notifications</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                Beautiful branded email templates for invoices, receipts, and status updates.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Custom branding</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Invoice + receipt templates</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>SMTP configuration</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="mailto:ruhidjavadoff@gmail.com?subject=Email%20Notifications%20Inquiry"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<div class="mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="font-mono text-[10px] uppercase tracking-[0.3em] text-purple-500">// Plugins & Integrations</div>
        <div class="flex-1 h-px bg-gradient-to-r from-purple-200 to-transparent"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-violet-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-comments text-lg"></i>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-200 text-[10px] font-mono uppercase tracking-wider text-emerald-700">New</span>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Live Chat Support</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                Real-time chat widget on your storefront so customers can reach you instantly.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Real human agents</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Offline message capture</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Desktop &amp; mobile</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="https://wa.me/994506636031?text=Hi!%20I%20am%20interested%20in%20Live%20Chat" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                </div>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Widget Orders Plugin</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                Turn any homepage widget into a direct order form — one-click checkout for your customers.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>One-click order from homepage</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Product + variant selection</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Instant cart integration</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="https://wa.me/994506636031?text=Hi!%20I%20am%20interested%20in%20Widget%20Orders" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl border border-gray-200 p-5 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-100/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white shadow-lg shadow-slate-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-plug text-lg"></i>
                </div>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Other Plugin Activation</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed mb-4">
                Need any specific plugin or custom integration? We build and wire it into your store.
            </p>
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Payment gateways</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Shipping providers</span>
                </li>
                <li class="flex items-start gap-2 text-[12px] text-gray-600">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                    <span>Custom API integrations</span>
                </li>
            </ul>
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400 font-mono uppercase tracking-wider">Custom pricing</span>
                <a href="https://wa.me/994506636031?text=Hi!%20I%20need%20a%20custom%20plugin" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Inquire <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 rounded-2xl p-6 md:p-10 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-pink-500/20 rounded-full blur-[100px]"></div>

    <div class="relative max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-[10px] font-mono uppercase tracking-[0.25em] text-indigo-300 mb-4">
            <span class="w-1.5 h-1.5 bg-pink-400 rounded-full animate-pulse"></span>
            Custom Request
        </div>
        <h3 class="text-2xl md:text-3xl font-black mb-3 leading-tight">Need something custom?</h3>
        <p class="text-white/70 text-sm leading-relaxed mb-8 max-w-xl">
            Every store is different. If you need a feature not listed above, or want to combine multiple services — just reach out. We'll give you a tailored solution.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            <a href="https://wa.me/994506636031" target="_blank"
               class="group flex items-center gap-4 p-4 bg-white/[0.04] border border-white/10 rounded-xl hover:bg-white/[0.08] hover:border-emerald-500/40 transition-all">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shrink-0">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-mono uppercase tracking-widest text-emerald-400 mb-0.5">WhatsApp</p>
                    <p class="text-sm font-semibold truncate">050 663 60 31</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-white/40 group-hover:text-white transition"></i>
            </a>

            <a href="https://t.me/veb_developher_az" target="_blank"
               class="group flex items-center gap-4 p-4 bg-white/[0.04] border border-white/10 rounded-xl hover:bg-white/[0.08] hover:border-sky-500/40 transition-all">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-lg shrink-0">
                    <i class="fa-brands fa-telegram text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-mono uppercase tracking-widest text-sky-400 mb-0.5">Telegram</p>
                    <p class="text-sm font-semibold truncate">@veb_developher_az</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-white/40 group-hover:text-white transition"></i>
            </a>

            <a href="mailto:ruhidjavadoff@gmail.com"
               class="group flex items-center gap-4 p-4 bg-white/[0.04] border border-white/10 rounded-xl hover:bg-white/[0.08] hover:border-rose-500/40 transition-all">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-lg shrink-0">
                    <i class="fa-solid fa-envelope text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-mono uppercase tracking-widest text-rose-400 mb-0.5">Email</p>
                    <p class="text-sm font-semibold truncate">ruhidjavadoff@gmail.com</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-white/40 group-hover:text-white transition"></i>
            </a>

            <a href="mailto:ruhidjavadov@gmail.com"
               class="group flex items-center gap-4 p-4 bg-white/[0.04] border border-white/10 rounded-xl hover:bg-white/[0.08] hover:border-rose-500/40 transition-all">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-lg shrink-0">
                    <i class="fa-solid fa-envelope text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-mono uppercase tracking-widest text-rose-400 mb-0.5">Email</p>
                    <p class="text-sm font-semibold truncate">ruhidjavadov@gmail.com</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-white/40 group-hover:text-white transition"></i>
            </a>

        </div>

        <p class="mt-8 text-[11px] text-white/50 font-mono uppercase tracking-wider text-center md:text-left">
            Response time · Usually within 1 hour · Mon-Sat 9am-9pm
        </p>
    </div>
</div>

@endsection

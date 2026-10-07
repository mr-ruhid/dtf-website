@extends('admin.app')

@section('title', 'Payment Methods')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Payment Methods</h2>
        <p class="text-sm text-gray-500 mt-1">Manage payment gateways and configure their credentials.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    @if(count($gateways) === 0)
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-credit-card text-gray-400 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 text-base mb-1">No payment gateways found</h3>
            <p class="text-sm text-gray-500">Drop a payment gateway folder into <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">app/Payment/Gateways/</code> to get started.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($gateways as $id => $gateway)
                @php
                    $isEnabled = $methods[$id] ?? false;
                @endphp

                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200 flex flex-col">

                    <div class="p-6 flex-1">

                        <div class="flex items-start justify-between gap-3 mb-5">

                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0
                                {{ $isEnabled ? 'bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-gray-100 text-gray-500' }}">
                                <i class="{{ $gateway->getIcon() }} text-xl"></i>
                            </div>

                            @if($isEnabled)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Inactive
                                </span>
                            @endif

                        </div>

                        <h3 class="font-bold text-gray-900 text-base mb-1">{{ $gateway->getName() }}</h3>
                        <p class="text-sm text-gray-500 leading-snug mb-4">{{ $gateway->getDescription() }}</p>

                        <div class="flex flex-wrap items-center gap-2 text-[10px] font-mono uppercase tracking-wider">
                            <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-600">
                                v{{ $gateway->getVersion() }}
                            </span>
                            <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-600">
                                {{ strtoupper($gateway->getMode()) }}
                            </span>
                            @if($gateway->supportsRefund())
                                <span class="px-2 py-1 rounded-md bg-blue-50 text-blue-600 border border-blue-100">
                                    Refunds
                                </span>
                            @endif
                        </div>

                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">

                        <a href="{{ route('admin.payments.show', $id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:border-indigo-300 hover:text-indigo-600 text-gray-700 text-xs font-semibold rounded-lg transition">
                            <i class="fa-solid fa-gear text-xs"></i>
                            <span>Configure</span>
                        </a>

                        <form method="POST" action="{{ route('admin.payments.toggle', $id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg transition
                                        {{ $isEnabled
                                            ? 'bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100'
                                            : 'bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent text-white hover:shadow-lg hover:shadow-indigo-500/30' }}">
                                <i class="fa-solid {{ $isEnabled ? 'fa-power-off' : 'fa-bolt' }} text-xs"></i>
                                <span>{{ $isEnabled ? 'Disable' : 'Enable' }}</span>
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection

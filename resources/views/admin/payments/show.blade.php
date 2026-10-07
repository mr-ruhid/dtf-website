@extends('admin.app')

@section('title', $gateway->getName() . ' — Payment Settings')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition">
            <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Back to Payment Methods
        </a>
    </div>

    @if(session('status'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-br from-indigo-50/50 to-purple-50/30">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0
                    {{ $enabled ? 'bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-gray-100 text-gray-500' }}">
                    <i class="{{ $gateway->getIcon() }} text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="font-bold text-gray-900 text-lg">{{ $gateway->getName() }}</h3>
                        @if($enabled)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                                Inactive
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $gateway->getDescription() }}</p>
                    <div class="flex items-center gap-3 mt-2 text-[10px] font-mono uppercase tracking-wider text-gray-500">
                        <span>ID: {{ $gateway->getId() }}</span>
                        <span class="text-gray-300">·</span>
                        <span>v{{ $gateway->getVersion() }}</span>
                        <span class="text-gray-300">·</span>
                        <span>by {{ $gateway->getAuthor() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.payments.update', $gateway->getId()) }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">

                <div class="flex items-start gap-4 p-5 bg-indigo-50 border border-indigo-200 rounded-xl">
                    <i class="fa-solid fa-toggle-on text-indigo-600 text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <label class="flex items-center justify-between cursor-pointer gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Enable this payment method</p>
                                <p class="text-xs text-gray-600 mt-1">Customers will see this option at checkout when enabled.</p>
                            </div>
                            <input type="checkbox"
                                   name="enabled"
                                   value="1"
                                   {{ $enabled ? 'checked' : '' }}
                                   class="w-12 h-6 rounded-full appearance-none bg-gray-300 checked:bg-indigo-600 relative cursor-pointer transition-colors shrink-0
                                          before:content-[''] before:absolute before:top-0.5 before:left-0.5 before:w-5 before:h-5 before:bg-white before:rounded-full before:shadow before:transition-transform before:duration-200
                                          checked:before:translate-x-6">
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Mode
                    </label>
                    <select name="mode"
                            class="w-full max-w-xs px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="test" {{ $mode === 'test' ? 'selected' : '' }}>Test / Sandbox</option>
                        <option value="live" {{ $mode === 'live' ? 'selected' : '' }}>Live / Production</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Use test mode while developing. Switch to live when ready.</p>
                </div>

                @if(count($schema) > 0)
                    <div class="border-t border-gray-100 pt-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="fa-solid fa-sliders text-gray-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Gateway Settings</h4>
                                <p class="text-xs text-gray-500">Credentials and behavior for this payment method.</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            @foreach($schema as $key => $field)
                                @php
                                    $type = $field['type'] ?? 'text';
                                    $label = $field['label'] ?? $key;
                                    $placeholder = $field['placeholder'] ?? '';
                                    $required = $field['required'] ?? false;
                                    $help = $field['help'] ?? null;
                                    $value = old('settings.' . $key, $settings[$key] ?? ($field['default'] ?? ''));
                                @endphp

                                <div>
                                    <label for="setting_{{ $key }}" class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ $label }}
                                        @if($required)
                                            <span class="text-rose-500 ml-1">*</span>
                                        @endif
                                    </label>

                                    @if($type === 'textarea')
                                        <textarea
                                            id="setting_{{ $key }}"
                                            name="settings[{{ $key }}]"
                                            rows="{{ $field['rows'] ?? 4 }}"
                                            placeholder="{{ $placeholder }}"
                                            {{ $required ? 'required' : '' }}
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-mono resize-y">{{ $value }}</textarea>

                                    @elseif($type === 'select')
                                        <select
                                            id="setting_{{ $key }}"
                                            name="settings[{{ $key }}]"
                                            {{ $required ? 'required' : '' }}
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                            @foreach(($field['options'] ?? []) as $optValue => $optLabel)
                                                @php
                                                    $optionValue = is_int($optValue) ? $optLabel : $optValue;
                                                    $optionLabel = is_int($optValue) ? $optLabel : $optLabel;
                                                @endphp
                                                <option value="{{ $optionValue }}" {{ (string) $value === (string) $optionValue ? 'selected' : '' }}>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>

                                    @elseif($type === 'number')
                                        <input
                                            type="number"
                                            id="setting_{{ $key }}"
                                            name="settings[{{ $key }}]"
                                            value="{{ $value }}"
                                            placeholder="{{ $placeholder }}"
                                            @if(isset($field['min'])) min="{{ $field['min'] }}" @endif
                                            @if(isset($field['max'])) max="{{ $field['max'] }}" @endif
                                            @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                                            {{ $required ? 'required' : '' }}
                                            class="w-full max-w-xs px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">

                                    @elseif($type === 'password')
                                        <input
                                            type="password"
                                            id="setting_{{ $key }}"
                                            name="settings[{{ $key }}]"
                                            value="{{ $value }}"
                                            placeholder="{{ $placeholder }}"
                                            {{ $required ? 'required' : '' }}
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">

                                    @elseif($type === 'toggle' || $type === 'boolean')
                                        <label class="flex items-center justify-between max-w-xs cursor-pointer gap-4 p-3 border border-gray-300 rounded-lg">
                                            <span class="text-sm text-gray-700">{{ $field['toggle_label'] ?? 'Enabled' }}</span>
                                            <input
                                                type="checkbox"
                                                name="settings[{{ $key }}]"
                                                value="1"
                                                {{ $value ? 'checked' : '' }}
                                                class="w-11 h-6 rounded-full appearance-none bg-gray-300 checked:bg-indigo-600 relative cursor-pointer transition-colors shrink-0
                                                       before:content-[''] before:absolute before:top-0.5 before:left-0.5 before:w-5 before:h-5 before:bg-white before:rounded-full before:shadow before:transition-transform before:duration-200
                                                       checked:before:translate-x-5">
                                        </label>

                                    @else
                                        <input
                                            type="text"
                                            id="setting_{{ $key }}"
                                            name="settings[{{ $key }}]"
                                            value="{{ $value }}"
                                            placeholder="{{ $placeholder }}"
                                            {{ $required ? 'required' : '' }}
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    @endif

                                    @if($help)
                                        <p class="text-xs text-gray-500 mt-2">{{ $help }}</p>
                                    @endif

                                    @error('settings.' . $key)
                                        <p class="text-xs text-rose-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-100 pt-6">
                        <div class="p-5 bg-gray-50 border border-gray-200 rounded-xl text-center">
                            <p class="text-sm text-gray-500">This gateway has no additional settings.</p>
                        </div>
                    </div>
                @endif

            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.payments.index') }}"
                   class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:shadow-indigo-500/40 transition">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>

        </form>

    </div>

</div>

@endsection

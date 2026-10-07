@extends('admin.layouts.app')

@section('title', 'Design Pricing')

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Design Pricing</h1>
        <p class="text-sm text-gray-500 mt-1">Configure custom sheet size pricing for the Design Studio.</p>
    </div>

    @if(session('status'))
        <div class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-lg">
            <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5"></i>
            <ul class="text-sm text-red-800 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.design-pricing.update') }}">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center">
                        <i class="fa-solid fa-ruler-combined text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Custom Size Pricing</h2>
                        <p class="text-xs text-gray-500">Applied only when a customer picks a size not in the print zones list.</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <div class="flex items-start gap-3 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <i class="fa-solid fa-toggle-on text-indigo-600 mt-0.5"></i>
                    <div class="flex-1">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Enable Custom Size</p>
                                <p class="text-xs text-gray-600 mt-0.5">Allow customers to enter their own sheet dimensions.</p>
                            </div>
                            <input type="checkbox"
                                   name="design_custom_enabled"
                                   value="1"
                                   {{ ($settings['design_custom_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                   class="w-11 h-6 rounded-full appearance-none bg-gray-300 checked:bg-indigo-600 relative cursor-pointer transition-colors
                                          before:content-[''] before:absolute before:top-0.5 before:left-0.5 before:w-5 before:h-5 before:bg-white before:rounded-full before:transition-transform
                                          checked:before:translate-x-5">
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Price per Square Inch
                        <span class="text-xs font-normal text-gray-500">($)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-mono">$</span>
                        <input type="number"
                               step="0.001"
                               min="0"
                               max="100"
                               name="design_custom_price_per_sq_inch"
                               value="{{ old('design_custom_price_per_sq_inch', $settings['design_custom_price_per_sq_inch'] ?? '0.05') }}"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Charged per square inch of the selected sheet (e.g. 22 × 24 in = 528 sq in).</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Minimum Price
                        <span class="text-xs font-normal text-gray-500">($)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-mono">$</span>
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="10000"
                               name="design_custom_min_price"
                               value="{{ old('design_custom_min_price', $settings['design_custom_min_price'] ?? '4.50') }}"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Applied if the calculated price is lower than this amount.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Minimum Dimension
                            <span class="text-xs font-normal text-gray-500">(inch)</span>
                        </label>
                        <input type="number"
                               step="0.1"
                               min="1"
                               max="60"
                               name="design_custom_min_inch"
                               value="{{ old('design_custom_min_inch', $settings['design_custom_min_inch'] ?? '1') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Maximum Dimension
                            <span class="text-xs font-normal text-gray-500">(inch)</span>
                        </label>
                        <input type="number"
                               step="0.1"
                               min="1"
                               max="200"
                               name="design_custom_max_inch"
                               value="{{ old('design_custom_max_inch', $settings['design_custom_max_inch'] ?? '60') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-xs text-amber-800 leading-relaxed">
                        <strong>Note:</strong> If a customer selects a size that matches an existing print zone, that zone's own price will be used instead of the formula. The formula applies only for custom sizes.
                    </p>
                </div>

            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-600 hover:text-gray-900 inline-flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back to settings</span>
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:shadow-indigo-500/40 transition">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>

        </div>

    </form>

</div>

@endsection

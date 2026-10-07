@extends('admin.settings.layout')

@section('title', 'Design Pricing')

@section('settings-content')

<form method="POST" action="{{ route('admin.settings.design-pricing.update') }}">
    @csrf

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-br from-indigo-50/50 to-pink-50/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-calculator text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-base">Custom Size Pricing</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Applied only when a customer picks a size not in the print zones list.</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">

            <div class="flex items-start gap-4 p-5 bg-indigo-50 border border-indigo-200 rounded-xl">
                <i class="fa-solid fa-toggle-on text-indigo-600 text-lg mt-0.5"></i>
                <div class="flex-1">
                    <label class="flex items-center justify-between cursor-pointer gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Enable Custom Size</p>
                            <p class="text-xs text-gray-600 mt-1">Allow customers to enter their own sheet dimensions on product pages.</p>
                        </div>
                        <input type="checkbox"
                               name="design_custom_enabled"
                               value="1"
                               {{ ($settings['design_custom_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                               class="w-12 h-6 rounded-full appearance-none bg-gray-300 checked:bg-indigo-600 relative cursor-pointer transition-colors shrink-0
                                      before:content-[''] before:absolute before:top-0.5 before:left-0.5 before:w-5 before:h-5 before:bg-white before:rounded-full before:shadow before:transition-transform before:duration-200
                                      checked:before:translate-x-6">
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    Price per Square Inch
                    <span class="text-xs font-normal text-gray-500 ml-1">(USD)</span>
                </label>
                <div class="relative max-w-xs">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-mono text-sm">$</span>
                    <input type="number"
                           step="0.001"
                           min="0"
                           max="100"
                           name="design_custom_price_per_sq_inch"
                           value="{{ old('design_custom_price_per_sq_inch', $settings['design_custom_price_per_sq_inch'] ?? '0.05') }}"
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    Charged per square inch of the selected sheet. Example: <span class="font-mono">22 × 24 in = 528 sq in × $0.05 = $26.40</span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    Minimum Price
                    <span class="text-xs font-normal text-gray-500 ml-1">(USD)</span>
                </label>
                <div class="relative max-w-xs">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-mono text-sm">$</span>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="10000"
                           name="design_custom_min_price"
                           value="{{ old('design_custom_min_price', $settings['design_custom_min_price'] ?? '4.50') }}"
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <p class="text-xs text-gray-500 mt-2">Applied if the calculated price is lower than this amount.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Minimum Dimension
                        <span class="text-xs font-normal text-gray-500 ml-1">(inch)</span>
                    </label>
                    <input type="number"
                           step="0.1"
                           min="1"
                           max="60"
                           name="design_custom_min_inch"
                           value="{{ old('design_custom_min_inch', $settings['design_custom_min_inch'] ?? '1') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-2">Smallest allowed width / height.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Maximum Dimension
                        <span class="text-xs font-normal text-gray-500 ml-1">(inch)</span>
                    </label>
                    <input type="number"
                           step="0.1"
                           min="1"
                           max="200"
                           name="design_custom_max_inch"
                           value="{{ old('design_custom_max_inch', $settings['design_custom_max_inch'] ?? '60') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-2">Largest allowed width / height.</p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <i class="fa-solid fa-lightbulb text-amber-600 mt-0.5"></i>
                <p class="text-xs text-amber-900 leading-relaxed">
                    <strong>Note:</strong> If a customer selects a size that matches an existing <em>print zone</em>, that zone's own price will be used instead of this formula. This pricing applies only for custom sizes entered manually.
                </p>
            </div>

        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
            <button type="reset"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                Reset
            </button>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:shadow-indigo-500/40 transition">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>

    </div>

</form>

@endsection

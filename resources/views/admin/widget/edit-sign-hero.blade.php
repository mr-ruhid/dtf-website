@extends('admin.app')

@section('title', 'Edit Sign Hero Widget')

@section('content')

<div x-data="signHeroEditor({{ json_encode($widget->settings ?? []) }})">

    <div class="mb-6">
        <a href="{{ route('admin.widgets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to widgets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800">Edit Sign Hero Widget</h2>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">
                <i class="fa-solid fa-puzzle-piece text-[9px]"></i> {{ $widget->name }}
            </span>
        </div>
        <p class="text-xs text-gray-400 font-mono mt-1">{{ $widget->key }}</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.widgets.update', $widget) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                {{-- HEADER --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-heading text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Hero Header</h3>
                            <p class="text-xs text-gray-400">Main title and eyebrow</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="eyebrow" x-model="form.eyebrow" maxlength="100"
                               placeholder="Custom Signage Studio"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" x-model="form.title" maxlength="200"
                               placeholder="Signs that make your brand unmissable"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Highlight Word
                            <span class="text-xs font-normal text-gray-400 ml-1">(shown with gradient)</span>
                        </label>
                        <input type="text" name="title_highlight" x-model="form.title_highlight" maxlength="100"
                               placeholder="unmissable"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-[10px] text-gray-400 mt-1">This word inside the title gets a gradient color</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" x-model="form.description" rows="3" maxlength="500"
                                  placeholder="Premium vinyl, banners and fully custom signs..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-hand-pointer text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Action Buttons</h3>
                            <p class="text-xs text-gray-400">Primary and secondary CTA</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Button 1 (Primary)</p>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Text</label>
                                <input type="text" name="btn1_text" x-model="form.btn1_text" maxlength="50"
                                       placeholder="Get a Free Quote"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">URL</label>
                                <input type="text" name="btn1_url" x-model="form.btn1_url" maxlength="255"
                                       placeholder="/contact-us"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Button 2 (Outline)</p>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Text</label>
                                <input type="text" name="btn2_text" x-model="form.btn2_text" maxlength="50"
                                       placeholder="View Portfolio"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">URL</label>
                                <input type="text" name="btn2_url" x-model="form.btn2_url" maxlength="255"
                                       placeholder="#portfolio"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STATS --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-chart-simple text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Stats Bar</h3>
                            <p class="text-xs text-gray-400">Three numbers below the buttons</p>
                        </div>
                    </div>

                    @for ($i = 1; $i <= 3; $i++)
                        <div class="grid grid-cols-3 gap-3 pb-3 {{ $i < 3 ? 'border-b border-gray-100' : '' }}">
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Value</label>
                                <input type="text" name="stat{{ $i }}_value" x-model="form.stat{{ $i }}_value" maxlength="20"
                                       placeholder="15+"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                                <input type="text" name="stat{{ $i }}_label" x-model="form.stat{{ $i }}_label" maxlength="50"
                                       placeholder="Years"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- CARDS --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Floating Cards</h3>
                            <p class="text-xs text-gray-400">Three cards with icon, title and meta</p>
                        </div>
                    </div>

                    @for ($i = 1; $i <= 3; $i++)
                        <div class="pb-3 {{ $i < 3 ? 'border-b border-gray-100' : '' }}">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Card {{ $i }}</p>
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-4">
                                    <label class="block text-[10px] font-medium text-gray-500 mb-1">Icon (FA class)</label>
                                    <input type="text" name="card{{ $i }}_icon" x-model="form.card{{ $i }}_icon" maxlength="50"
                                           placeholder="fa-solid fa-sign-hanging"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-5">
                                    <label class="block text-[10px] font-medium text-gray-500 mb-1">Title</label>
                                    <input type="text" name="card{{ $i }}_title" x-model="form.card{{ $i }}_title" maxlength="100"
                                           placeholder="Storefront Signs"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-3">
                                    <label class="block text-[10px] font-medium text-gray-500 mb-1">Meta</label>
                                    <input type="text" name="card{{ $i }}_meta" x-model="form.card{{ $i }}_meta" maxlength="50"
                                           placeholder="From $149"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Status</h3>

                    <div class="flex items-center gap-3 px-3 py-3 rounded-lg border {{ $widget->is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="w-8 h-8 rounded-lg {{ $widget->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center">
                            <i class="fa-solid {{ $widget->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }} text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $widget->is_active ? 'Active' : 'Inactive' }}</p>
                            <p class="text-xs text-gray-500">Toggle from widgets list</p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.widgets.index') }}"
                           class="block text-center text-sm text-gray-600 hover:text-gray-800 py-2">Cancel</a>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                    <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                    <div class="text-xs text-amber-800">
                        <p class="font-medium mb-1">Icons</p>
                        <p>Use Font Awesome 6 classes. Examples:</p>
                        <ul class="mt-1 space-y-0.5 font-mono text-[10px]">
                            <li>fa-solid fa-sign-hanging</li>
                            <li>fa-solid fa-scroll</li>
                            <li>fa-solid fa-truck-fast</li>
                            <li>fa-solid fa-cube</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function signHeroEditor(settings) {
    var s = settings || {};

    return {
        form: {
            eyebrow: s.eyebrow || 'Custom Signage Studio',
            title: s.title || 'Signs that make your brand unmissable',
            title_highlight: s.title_highlight || 'unmissable',
            description: s.description || 'Premium vinyl, banners and fully custom signs — designed, printed and installed by professionals.',
            btn1_text: s.btn1_text || 'Get a Free Quote',
            btn1_url: s.btn1_url || '/contact-us',
            btn2_text: s.btn2_text || 'View Portfolio',
            btn2_url: s.btn2_url || '#portfolio',
            stat1_value: s.stat1_value || '15+',
            stat1_label: s.stat1_label || 'Years Experience',
            stat2_value: s.stat2_value || '8K+',
            stat2_label: s.stat2_label || 'Signs Produced',
            stat3_value: s.stat3_value || '24h',
            stat3_label: s.stat3_label || 'Rush Turnaround',
            card1_icon: s.card1_icon || 'fa-solid fa-sign-hanging',
            card1_title: s.card1_title || 'Storefront Signs',
            card1_meta: s.card1_meta || 'From $149',
            card2_icon: s.card2_icon || 'fa-solid fa-scroll',
            card2_title: s.card2_title || 'Vinyl Banners',
            card2_meta: s.card2_meta || 'From $39',
            card3_icon: s.card3_icon || 'fa-solid fa-truck-fast',
            card3_title: s.card3_title || 'Install Service',
            card3_meta: s.card3_meta || 'Same Day',
        }
    };
}
</script>

@endsection

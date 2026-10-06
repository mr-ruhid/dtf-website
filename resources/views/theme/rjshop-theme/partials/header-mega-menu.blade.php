@php
    $megaModels = \App\Models\ProductModel::with(['categories' => function($q) {
        $q->whereNull('parent_id')->where('status', 1)->with('children')->orderBy('sort_order');
    }])->inHeader()->get();
@endphp

@if($megaModels->count())
<div class="relative bg-[#0a0715] border-b border-indigo-500/10 z-40" x-data="{ activeModel: null }" @mouseleave="activeModel = null">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-center gap-1 overflow-x-auto scrollbar-hide">
            @foreach($megaModels as $megaModel)
                <div @mouseenter="activeModel = {{ $megaModel->id }}">
                    <a href="{{ url($megaModel->slug) }}"
                       class="group relative inline-flex items-center gap-2 px-5 py-3 text-sm font-medium whitespace-nowrap transition-colors"
                       :class="activeModel === {{ $megaModel->id }} ? 'text-white' : 'text-gray-400 hover:text-white'">
                        @if($megaModel->icon)
                            <i class="fa-solid {{ $megaModel->icon }} text-xs"></i>
                        @endif
                        <span>{{ $megaModel->name }}</span>
                        @if($megaModel->categories->count())
                            <i class="fa-solid fa-chevron-down text-[9px] transition-transform"
                               :class="activeModel === {{ $megaModel->id }} ? 'rotate-180' : ''"></i>
                        @endif
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-[2px] bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 transition-all duration-300"
                              :class="activeModel === {{ $megaModel->id }} ? 'w-full' : 'w-0'"></span>
                    </a>
                </div>
            @endforeach
        </nav>
    </div>

    @foreach($megaModels as $megaModel)
        @if($megaModel->categories->count())
            <div x-show="activeModel === {{ $megaModel->id }}"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="absolute top-full left-0 right-0 bg-[#0a0715]/98 backdrop-blur-xl border-b border-indigo-500/20 shadow-2xl"
                 style="box-shadow: 0 20px 60px rgba(0,0,0,0.6);">

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="grid grid-cols-12 gap-8">

                        <div class="col-span-12 md:col-span-3">
                            <div class="relative rounded-xl overflow-hidden border border-white/10 group">
                                @if($megaModel->image)
                                    <img src="{{ $megaModel->image_url }}" alt="{{ $megaModel->name }}"
                                         class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="aspect-square bg-gradient-to-br from-indigo-600/30 to-pink-600/30 flex items-center justify-center">
                                        <i class="fa-solid fa-cube text-white/20 text-6xl"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0715] via-transparent to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <p class="font-mono text-[9px] text-indigo-300 uppercase tracking-widest mb-1">Featured</p>
                                    <p class="text-white font-bold text-lg">{{ $megaModel->name }}</p>
                                </div>
                            </div>

                            <a href="{{ url($megaModel->slug) }}"
                               class="mt-3 inline-flex items-center justify-between w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition group">
                                <span>Browse All</span>
                                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition"></i>
                            </a>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="font-mono text-[10px] text-indigo-400 uppercase tracking-[0.3em]">// Categories</span>
                                <span class="flex-1 h-px bg-gradient-to-r from-indigo-500/40 to-transparent"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                                @foreach($megaModel->categories as $category)
                                    <div>
                                        <a href="{{ url($megaModel->slug . '/' . $category->slug) }}"
                                           class="inline-flex items-center gap-2 text-sm font-semibold text-white hover:text-indigo-300 transition group mb-2">
                                            <span class="w-1 h-1 bg-indigo-400 rounded-full group-hover:scale-150 transition"></span>
                                            <span>{{ $category->name }}</span>
                                        </a>
                                        @if($category->children->count())
                                            <ul class="ml-3 space-y-1.5 border-l border-white/5 pl-3">
                                                @foreach($category->children as $child)
                                                    <li>
                                                        <a href="{{ url($megaModel->slug . '/' . $category->slug . '/' . $child->slug) }}"
                                                           class="text-[13px] text-gray-400 hover:text-white transition inline-block">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="font-mono text-[10px] text-pink-400 uppercase tracking-[0.3em]">// Popular</span>
                                <span class="flex-1 h-px bg-gradient-to-r from-pink-500/40 to-transparent"></span>
                            </div>

                            <div class="space-y-2">
                                @foreach($megaModel->categories->take(3) as $cat)
                                    <a href="{{ url($megaModel->slug . '/' . $cat->slug) }}"
                                       class="flex items-center gap-3 p-2.5 rounded-lg bg-white/[0.02] border border-white/5 hover:border-indigo-500/30 hover:bg-white/[0.04] transition group">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-[#0a0715] border border-white/5 shrink-0">
                                            @if($cat->image)
                                                <img src="{{ $cat->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-white/10">
                                                    <i class="fa-solid fa-folder text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[13px] font-medium text-white truncate">{{ $cat->name }}</p>
                                            <p class="text-[10px] text-gray-500 font-mono">Explore →</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <div class="border-t border-white/5">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between text-[10px] font-mono uppercase tracking-widest text-gray-500">
                        <span class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 8px 2px rgba(52,211,153,0.9);"></span>
                            In Stock
                        </span>
                        <span>Same Day Production</span>
                        <span>Free Shipping $99+</span>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endif

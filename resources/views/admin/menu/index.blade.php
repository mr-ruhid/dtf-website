@extends('admin.app')

@section('title', 'Menus')

@section('content')

<div x-data="{
    showModal: false,
    editing: null,
    formAction: '{{ route('admin.menus.store') }}',
    formMethod: 'POST'
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Menus</h2>
            <p class="text-sm text-gray-500 mt-1">Manage navigation menus for header, footer and custom locations</p>
        </div>
        <button @click="showModal = true; editing = null; formAction = '{{ route('admin.menus.store') }}'; formMethod = 'POST'"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> New Menu
        </button>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($menus->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-bars-staggered text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm mb-1">No menus yet.</p>
            <p class="text-gray-400 text-xs">Create your first menu to start building navigation.</p>
            <button @click="showModal = true"
                    class="inline-flex items-center gap-2 mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus text-xs"></i> Create Menu
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($menus as $menu)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-bars-staggered text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $menu->name }}</h3>
                            <p class="text-[11px] text-gray-400 font-mono truncate">{{ $menu->slug }}</p>
                        </div>
                        @if (!$menu->status)
                            <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded font-medium">Off</span>
                        @endif
                    </div>

                    <div class="px-5 py-4 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Location</span>
                            <span class="font-mono px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded">{{ $menu->location }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Items</span>
                            <span class="font-semibold text-gray-800">{{ $menu->items_count }}</span>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center gap-1.5">
                        <a href="{{ route('admin.menus.show', $menu) }}"
                           class="flex-1 text-center text-xs bg-indigo-600 hover:bg-indigo-700 text-white py-1.5 rounded transition">
                            <i class="fa-solid fa-pen-to-square text-[10px] mr-1"></i> Manage Items
                        </a>
                        <button @click='showModal = true; editing = @json($menu); formAction = "{{ route('admin.menus.update', $menu) }}"; formMethod = "PUT"'
                                class="w-8 h-8 flex items-center justify-center rounded hover:bg-white text-gray-600 transition">
                            <i class="fa-solid fa-gear text-xs"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.menus.toggle', $menu) }}">
                            @csrf
                            @method('PUT')
                            <button class="w-8 h-8 flex items-center justify-center rounded {{ $menu->status ? 'text-emerald-600 hover:bg-white' : 'text-gray-400 hover:bg-white' }} transition" title="Toggle status">
                                <i class="fa-solid {{ $menu->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Delete this menu and all its items?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-white text-red-600 transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Menu' : 'New Menu'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" class="p-6 space-y-4">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :value="editing?.name || ''" required
                           @input="if(!editing) { const slugInput = $el.form.querySelector('[name=slug]'); slugInput.value = $event.target.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '') }"
                           placeholder="Main Header"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" :value="editing?.slug || ''"
                           placeholder="main-header"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Used in code: <code class="bg-gray-100 px-1 rounded">Menu::bySlug('main-header')</code></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                    <select name="location" required :value="editing?.location || 'header'"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="header">Header</option>
                        <option value="model_bar">Model Bar (Sub-header)</option>
                        <option value="footer">Footer</option>
                        <option value="mobile">Mobile</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="status" value="1" :checked="editing ? editing.status : true"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Active
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editing ? 'Update' : 'Create'"></span>
                    </button>
                    <button type="button" @click="showModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

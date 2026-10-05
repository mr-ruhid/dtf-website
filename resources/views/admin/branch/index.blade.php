@extends('admin.app')

@section('title', 'Branches')

@section('content')

@php
    $branchesData = $branches->map(fn($b) => [
        'id' => $b->id,
        'name' => $b->name,
        'code' => $b->code,
        'phone' => $b->phone,
        'email' => $b->email,
        'address' => $b->address,
        'city' => $b->city,
        'state' => $b->state,
        'zip' => $b->zip,
        'country' => $b->country,
        'is_default' => (bool) $b->is_default,
        'sort_order' => $b->sort_order,
        'status' => (bool) $b->status,
    ])->values();
@endphp

<div x-data="{
    branches: @js($branchesData),
    showModal: false,
    editing: null,
    formAction: '',
    formMethod: 'POST',

    openModal(branch, action, method) {
        this.editing = branch;
        this.formAction = action;
        this.formMethod = method;
        this.showModal = true;
    }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Branches</h2>
            <p class="text-sm text-gray-500 mt-1">Manage warehouse locations and fulfillment centers</p>
        </div>
        <button @click="openModal(null, '{{ route('admin.branches.store') }}', 'POST')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> New Branch
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

    @if ($branches->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($branches as $branch)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition group
                    {{ $branch->is_default ? 'ring-2 ring-indigo-500/30 border-indigo-200' : '' }}">

                    @if ($branch->is_default)
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-1.5 flex items-center gap-2">
                            <i class="fa-solid fa-star text-amber-300 text-xs"></i>
                            <span class="text-white text-[10px] font-bold uppercase tracking-wider">Default Branch</span>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shrink-0">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $branch->name }}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-mono bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">{{ $branch->code }}</span>
                                    @if (!$branch->status)
                                        <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-medium">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs mb-4">
                            @if ($branch->phone)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fa-solid fa-phone text-gray-400 w-4"></i>
                                    <span class="truncate">{{ $branch->phone }}</span>
                                </div>
                            @endif
                            @if ($branch->email)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fa-solid fa-envelope text-gray-400 w-4"></i>
                                    <span class="truncate">{{ $branch->email }}</span>
                                </div>
                            @endif
                            @if ($branch->address || $branch->city)
                                <div class="flex items-start gap-2 text-gray-600">
                                    <i class="fa-solid fa-location-dot text-gray-400 w-4 mt-0.5"></i>
                                    <span class="line-clamp-2">
                                        {{ $branch->address }}
                                        @if ($branch->city), {{ $branch->city }}@endif
                                        @if ($branch->state), {{ $branch->state }}@endif
                                        @if ($branch->zip) {{ $branch->zip }}@endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center gap-1.5">
                            @if (!$branch->is_default)
                                <form method="POST" action="{{ route('admin.branches.default', $branch) }}" class="flex-1">
                                    @csrf
                                    @method('PUT')
                                    <button class="w-full text-[10px] text-indigo-600 hover:bg-indigo-50 py-1.5 rounded transition font-medium">
                                        <i class="fa-solid fa-star text-[9px] mr-1"></i> Set Default
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.branches.toggle', $branch) }}">
                                @csrf
                                @method('PUT')
                                <button class="w-8 h-8 flex items-center justify-center rounded {{ $branch->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                    <i class="fa-solid {{ $branch->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                                </button>
                            </form>

                            <button @click="openModal(branches.find(b => b.id === {{ $branch->id }}), '{{ route('admin.branches.update', $branch) }}', 'PUT')"
                                    class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>

                            @if (!$branch->is_default)
                                <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}"
                                      onsubmit="return confirm('Delete this branch?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-store text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-400 text-sm mb-1">No branches yet.</p>
            <p class="text-gray-400 text-xs">Create your first branch to start accepting orders.</p>
        </div>
    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Branch' : 'New Branch'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" class="p-6 space-y-4">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" :value="editing?.name || ''" required
                               placeholder="Main Warehouse"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" :value="editing?.code || ''" required
                               placeholder="MAIN"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" :value="editing?.phone || ''"
                               placeholder="+1 (555) 000-0000"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" :value="editing?.email || ''"
                               placeholder="branch@example.com"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" :value="editing?.address || ''"
                           placeholder="123 Main Street"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" :value="editing?.city || ''"
                               placeholder="Los Angeles"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" name="state" :value="editing?.state || ''" maxlength="10"
                               placeholder="CA"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ZIP</label>
                        <input type="text" name="zip" :value="editing?.zip || ''" maxlength="20"
                               placeholder="90001"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country Code</label>
                    <input type="text" name="country" :value="editing?.country || 'US'" maxlength="5"
                           placeholder="US"
                           class="w-full md:w-32 px-4 py-2.5 border border-gray-300 rounded-lg text-sm uppercase font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editing?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="space-y-2 pt-6">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editing ? editing.status : true"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_default" value="1" :checked="editing ? editing.is_default : false"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Default Branch
                        </label>
                    </div>
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

<style>[x-cloak]{display:none!important;}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}</style>

@endsection

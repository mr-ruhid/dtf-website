@extends('admin.app')

@section('title', 'FAQ')

@section('content')

<div x-data="{
    showModal: false,
    editing: null,
    formAction: '{{ route('admin.faqs.store') }}',
    formMethod: 'POST'
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">FAQ</h2>
            <p class="text-sm text-gray-500 mt-1">Manage frequently asked questions</p>
        </div>
        <button @click="showModal = true; editing = null; formAction = '{{ route('admin.faqs.store') }}'; formMethod = 'POST'"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Add FAQ
        </button>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="space-y-3">
        @forelse ($faqs as $faq)
            <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                    {{ $faq->sort_order }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-medium text-gray-800 truncate">{{ $faq->question }}</h3>
                        @if (!$faq->status)
                            <span class="px-2 py-0.5 rounded text-xs bg-gray-200 text-gray-600">Inactive</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $faq->answer }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.faqs.toggle', $faq) }}">
                        @csrf
                        @method('PUT')
                        <button class="w-8 h-8 flex items-center justify-center rounded {{ $faq->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                            <i class="fa-solid {{ $faq->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                        </button>
                    </form>
                    <button @click='showModal = true; editing = @json($faq); formAction = "{{ route('admin.faqs.update', $faq) }}"; formMethod = "PUT"'
                            class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                          onsubmit="return confirm('Delete this FAQ?')">
                        @csrf
                        @method('DELETE')
                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg border border-gray-200 py-12 text-center text-gray-400 text-sm">
                No FAQs yet. Click "Add FAQ" to create the first one.
            </div>
        @endforelse
    </div>

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-lg">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit FAQ' : 'Add FAQ'"></h3>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                    <input type="text" name="question" :value="editing?.question || ''" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                    <textarea name="answer" rows="4" required x-text="editing?.answer || ''"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editing?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editing ? editing.status : true"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
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

<style>
[x-cloak]{display:none!important;}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
</style>

@endsection

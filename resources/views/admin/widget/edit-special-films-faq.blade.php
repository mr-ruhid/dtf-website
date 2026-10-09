@extends('admin.app')

@section('title', 'Edit Special Films FAQ Widget')

@section('content')

<div x-data="specialFilmsFaqEditor({{ json_encode($widget->settings ?? []) }})">

    <div class="mb-6">
        <a href="{{ route('admin.widgets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to widgets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800">Edit Special Films FAQ Widget</h2>
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

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-heading text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Header</h3>
                            <p class="text-xs text-gray-400">Eyebrow + title on top of the FAQ list</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="eyebrow" x-model="eyebrow" maxlength="150"
                               placeholder="THE SAME ANSWERS, IF YOU NEED THEM"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" x-model="title" maxlength="200"
                               placeholder="Open a question"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-circle-question text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Questions</h3>
                            <p class="text-xs text-gray-400">Add question + answer pairs</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="border border-gray-200 rounded-lg p-3 space-y-2">
                                <div class="flex items-start gap-2">
                                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-1"
                                          x-text="index + 1"></span>
                                    <input type="text" :name="`items[${index}][question]`" x-model="item.question" maxlength="250"
                                           placeholder="Question"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <button type="button" @click="items.splice(index, 1)"
                                            class="w-9 h-9 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                                <textarea :name="`items[${index}][answer]`" x-model="item.answer" rows="3" maxlength="2000"
                                          placeholder="Answer (HTML allowed)"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </template>

                        <button type="button" @click="items.push({question:'', answer:''})"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Question
                        </button>
                    </div>
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
                        <p class="font-medium mb-1">Usage</p>
                        <p>This widget is included at the bottom of the <code class="bg-amber-100 px-1 rounded">special-films</code> model page.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function specialFilmsFaqEditor(settings) {
    var s = settings || {};

    return {
        eyebrow: s.eyebrow || 'THE SAME ANSWERS, IF YOU NEED THEM',
        title: s.title || 'Open a question',

        items: Array.isArray(s.items) && s.items.length ? s.items.map(function (x) {
            return { question: x.question || '', answer: x.answer || '' };
        }) : [
            { question: 'What specialty film does DTF Town sell?', answer: '' },
            { question: 'What is Glitter DTF?', answer: '' },
            { question: 'How do I press Glitter DTF?', answer: '' },
            { question: 'Where is Glitter DTF printed?', answer: '' },
        ],
    };
}
</script>

@endsection

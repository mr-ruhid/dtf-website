@extends('admin.app')

@section('title', 'Edit Special Films Story Widget')

@section('content')

<div x-data="specialFilmsStoryEditor({{ json_encode($widget->settings ?? []) }})">

    <div class="mb-6">
        <a href="{{ route('admin.widgets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to widgets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800">Edit Special Films Story Widget</h2>
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

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-gauge-high text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Stats Row</h3>
                            <p class="text-xs text-gray-400">Top stats: temperature, pressure, timing (up to 8)</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(stat, index) in stats" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" :name="`stats[${index}][value]`" x-model="stat.value" maxlength="30"
                                       placeholder="310°F"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <input type="text" :name="`stats[${index}][sub]`" x-model="stat.sub" maxlength="30"
                                       placeholder="155°C"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <input type="text" :name="`stats[${index}][label]`" x-model="stat.label" maxlength="60"
                                       placeholder="Temperature"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <button type="button" @click="stats.splice(index, 1)"
                                        class="w-9 h-9 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="stats.push({value:'', sub:'', label:''})"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Stat
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-heading text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Story Header</h3>
                            <p class="text-xs text-gray-400">Eyebrow + big title</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="story_eyebrow" x-model="story_eyebrow" maxlength="100"
                               placeholder="THE STORY"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="story_title" x-model="story_title" maxlength="200"
                               placeholder="Want it. Press it. Send the file."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-list-ol text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Story Steps</h3>
                            <p class="text-xs text-gray-400">Numbered steps (up to 10)</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="border border-gray-200 rounded-lg p-3 space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0"
                                          x-text="index + 1"></span>
                                    <input type="text" :name="`steps[${index}][title]`" x-model="step.title" maxlength="150"
                                           placeholder="Step title"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <button type="button" @click="steps.splice(index, 1)"
                                            class="w-9 h-9 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                                <textarea :name="`steps[${index}][description]`" x-model="step.description" rows="3" maxlength="2000"
                                          placeholder="Step description"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </template>

                        <button type="button" @click="steps.push({title:'', description:''})"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Step
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-link text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">CTA Button</h3>
                            <p class="text-xs text-gray-400">Bottom button text and URL</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input type="text" name="cta_text" x-model="cta_text" maxlength="100"
                                   placeholder="Upload your Glitter DTF design"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <input type="text" name="cta_url" x-model="cta_url" maxlength="255"
                                   placeholder="/design/glitter-dtf-transfers"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
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
                        <p>This widget is included on the <code class="bg-amber-100 px-1 rounded">special-films</code> model page.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function specialFilmsStoryEditor(settings) {
    var s = settings || {};

    return {
        stats: Array.isArray(s.stats) && s.stats.length ? s.stats.map(function (x) {
            return { value: x.value || '', sub: x.sub || '', label: x.label || '' };
        }) : [
            { value: '310°F', sub: '155°C', label: 'Temperature' },
            { value: 'Medium', sub: '', label: 'Pressure' },
            { value: '12–15 sec', sub: '', label: 'Press time' },
            { value: '~5 sec', sub: '', label: 'Then peel' },
            { value: '5–10 sec', sub: '', label: 'Second press' },
        ],

        story_eyebrow: s.story_eyebrow || 'THE STORY',
        story_title: s.story_title || 'Want it. Press it. Send the file.',

        steps: Array.isArray(s.steps) && s.steps.length ? s.steps.map(function (x) {
            return { title: x.title || '', description: x.description || '' };
        }) : [
            { title: 'You wanted sparkle', description: '' },
            { title: 'You press it the way you already know', description: '' },
            { title: 'Then you send the art', description: '' },
        ],

        cta_text: s.cta_text || 'Upload your Glitter DTF design',
        cta_url: s.cta_url || '/design/glitter-dtf-transfers',
    };
}
</script>

@endsection

@extends('admin.app')

@section('title', 'Edit Admin AI Chat Widget')

@section('content')

<div x-data="adminAiChatEditor({{ json_encode($widget->settings ?? []) }})">

    <div class="mb-6">
        <a href="{{ route('admin.widgets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to widgets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800">Edit Admin AI Chat</h2>
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

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-robot text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">AI Provider</h3>
                            <p class="text-xs text-gray-400">Choose which AI opens in the popup</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AI URL</label>
                        <input type="text" name="ai_url" x-model="ai_url" maxlength="500"
                               placeholder="https://chat.openai.com"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-[10px] text-gray-400 mt-1">
                            Full URL of the AI chat. Opens in a small popup window.
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-700 mb-2">Quick presets — test one by one</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="ai_url = 'https://chat.openai.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">ChatGPT</button>
                            <button type="button" @click="ai_url = 'https://claude.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Claude</button>
                            <button type="button" @click="ai_url = 'https://gemini.google.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Gemini</button>
                            <button type="button" @click="ai_url = 'https://chat.deepseek.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">DeepSeek</button>
                            <button type="button" @click="ai_url = 'https://copilot.microsoft.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Copilot</button>
                            <button type="button" @click="ai_url = 'https://www.perplexity.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Perplexity</button>
                            <button type="button" @click="ai_url = 'https://quillbot.com/ai-chat/'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">QuillBot</button>
                            <button type="button" @click="ai_url = 'https://grok.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Grok</button>
                            <button type="button" @click="ai_url = 'https://chat.mistral.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Mistral</button>
                            <button type="button" @click="ai_url = 'https://poe.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Poe</button>
                            <button type="button" @click="ai_url = 'https://you.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">You.com</button>
                            <button type="button" @click="ai_url = 'https://huggingface.co/chat'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">HF Chat</button>
                            <button type="button" @click="ai_url = 'https://chat.qwen.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Qwen</button>
                            <button type="button" @click="ai_url = 'https://chatglm.cn'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">ChatGLM</button>
                            <button type="button" @click="ai_url = 'https://kimi.moonshot.cn'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Kimi</button>
                            <button type="button" @click="ai_url = 'https://www.blackbox.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Blackbox</button>
                            <button type="button" @click="ai_url = 'https://pi.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Pi</button>
                            <button type="button" @click="ai_url = 'https://character.ai'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">Character.AI</button>
                            <button type="button" @click="ai_url = 'https://chatgpt.com'" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">ChatGPT.com</button>
                            <button type="button" @click="ai_url = 'https://example.com'" class="px-3 py-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-xs font-medium text-amber-800 transition">example.com (test)</button>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">
                            Add <code class="font-mono">?url=</code> in iframe src through proxy. Test each one and keep the working ones.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-window-restore text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Popup Window</h3>
                            <p class="text-xs text-gray-400">Size and position of the floating button</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Popup Width (px)</label>
                            <input type="number" name="popup_width" x-model.number="popup_width" min="300" max="1200"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Popup Height (px)</label>
                            <input type="number" name="popup_height" x-model.number="popup_height" min="400" max="1400"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Position</label>
                        <select name="position" x-model="position"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="bottom-right">Bottom Right</option>
                            <option value="bottom-left">Bottom Left</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Label</label>
                        <input type="text" name="button_label" x-model="button_label" maxlength="60"
                               placeholder="AI Assistant"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-[10px] text-gray-400 mt-1">Tooltip shown on hover</p>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Status</h3>

                    <label class="flex items-center justify-between p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-power-off text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Enabled</p>
                                <p class="text-[11px] text-gray-500">Show button on admin pages</p>
                            </div>
                        </div>
                        <input type="hidden" name="enabled" :value="enabled ? 1 : 0">
                        <input type="checkbox" x-model="enabled"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                    </label>

                    <div class="flex items-center gap-3 px-3 py-3 rounded-lg border {{ $widget->is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="w-8 h-8 rounded-lg {{ $widget->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center">
                            <i class="fa-solid {{ $widget->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }} text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $widget->is_active ? 'Widget Active' : 'Widget Inactive' }}</p>
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
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
                    <div class="text-xs text-amber-800">
                        <p class="font-medium mb-1">Heads up</p>
                        <p>Some AI providers (ChatGPT, Claude, Gemini) block embedding in iframes and small windows. The button opens them in a popup window instead — this works for all providers.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function adminAiChatEditor(settings) {
    var s = settings || {};

    return {
        enabled: s.enabled === true || s.enabled === 1 || s.enabled === '1',
        ai_url: s.ai_url || 'https://chat.openai.com',
        button_label: s.button_label || 'AI Assistant',
        popup_width: parseInt(s.popup_width) || 460,
        popup_height: parseInt(s.popup_height) || 780,
        position: s.position || 'bottom-right',
    };
}
</script>

@endsection
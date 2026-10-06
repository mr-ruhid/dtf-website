@php
    $name = $name ?? 'content';
    $id = $id ?? 'editor';
    $value = $value ?? '';
    $label = $label ?? 'Content (HTML)';
    $rows = $rows ?? 18;
    $height = $height ?? 500;
    $hint = $hint ?? 'Use the <> (code) button for raw HTML.';
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
        <div class="flex items-center gap-2">
            <button type="button"
                    onclick="if(confirm('Clear all content?')) { tinymce.get('{{ $id }}').setContent(''); }"
                    class="text-xs text-red-600 hover:bg-red-50 px-2 py-1 rounded transition">
                <i class="fa-solid fa-trash text-[10px] mr-1"></i> Clear
            </button>
            <span class="text-[10px] text-gray-400">HTML allowed</span>
        </div>
    </div>
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ $value }}</textarea>
    <p class="text-xs text-gray-500 mt-2">{!! $hint !!}</p>
</div>

@once
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
(function() {
    function initEditor_{{ str_replace('-', '_', $id) }}() {
        if (typeof tinymce === 'undefined') return;
        if (tinymce.get('{{ $id }}')) return;

        tinymce.init({
            selector: '#{{ $id }}',
            height: {{ $height }},
            menubar: false,
            plugins: 'lists link image code table preview fullscreen wordcount',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link image table | code fullscreen preview',
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
            branding: false,
            promotion: false,
            valid_elements: '*[*]',
            extended_valid_elements: '*[*]',
            verify_html: false,
            allow_script_urls: true,
            valid_children: '+body[style],+div[style]',
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditor_{{ str_replace('-', '_', $id) }});
    } else {
        initEditor_{{ str_replace('-', '_', $id) }}();
    }
})();
</script>
@endpush

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function index()
    {
        $widgets = Widget::orderBy('sort_order')->get();

        return view('admin.widget.index', compact('widgets'));
    }

    public function edit(Widget $widget)
    {
        return view('admin.widget.edit', compact('widget'));
    }

    public function update(Request $request, Widget $widget)
    {
        $data = $this->validateByKey($request, $widget->key);

        $widget->update([
            'settings' => $data,
        ]);

        return back()->with('status', 'Widget updated successfully.');
    }

    public function toggle(Widget $widget)
    {
        $widget->update(['is_active' => !$widget->is_active]);

        return back()->with('status', 'Widget status updated.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:widgets,id'],
        ]);

        foreach ($request->input('order') as $index => $id) {
            Widget::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    protected function validateByKey(Request $request, string $key): array
    {
        return match ($key) {
            'hero', 'slider_mid' => $request->validate([
                'location' => ['required', 'string', 'max:100'],
            ]),

            'steps' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'link_text' => ['nullable', 'string', 'max:50'],
                'link_url' => ['nullable', 'string', 'max:255'],
                'button_text' => ['nullable', 'string', 'max:50'],
                'button_url' => ['nullable', 'string', 'max:255'],
                'items' => ['nullable', 'array'],
                'items.*.number' => ['nullable', 'string', 'max:10'],
                'items.*.title' => ['nullable', 'string', 'max:100'],
                'items.*.description' => ['nullable', 'string', 'max:500'],
                'items.*.image' => ['nullable', 'string', 'max:500'],
            ]),

            'features' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'items' => ['nullable', 'array'],
                'items.*.icon' => ['nullable', 'string', 'max:50'],
                'items.*.title' => ['nullable', 'string', 'max:100'],
                'items.*.description' => ['nullable', 'string', 'max:500'],
            ]),

            'categories' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:24'],
            ]),

            'featured_products' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:24'],
            ]),

            'testimonials' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'items' => ['nullable', 'array'],
                'items.*.name' => ['nullable', 'string', 'max:100'],
                'items.*.role' => ['nullable', 'string', 'max:100'],
                'items.*.text' => ['nullable', 'string', 'max:500'],
                'items.*.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
                'items.*.avatar' => ['nullable', 'string', 'max:500'],
            ]),

            'stats' => $request->validate([
                'items' => ['nullable', 'array'],
                'items.*.icon' => ['nullable', 'string', 'max:50'],
                'items.*.value' => ['nullable', 'string', 'max:20'],
                'items.*.label' => ['nullable', 'string', 'max:50'],
            ]),

            'info' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'content' => ['nullable', 'string'],
                'button_text' => ['nullable', 'string', 'max:50'],
                'button_url' => ['nullable', 'string', 'max:255'],
            ]),

            'blog_preview' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            ]),

            'faq_preview' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
                'button_text' => ['nullable', 'string', 'max:50'],
                'button_url' => ['nullable', 'string', 'max:255'],
            ]),

            'newsletter' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'placeholder' => ['nullable', 'string', 'max:100'],
                'button_text' => ['nullable', 'string', 'max:50'],
            ]),

            'cta' => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'button1_text' => ['nullable', 'string', 'max:50'],
                'button1_url' => ['nullable', 'string', 'max:255'],
                'button2_text' => ['nullable', 'string', 'max:50'],
                'button2_url' => ['nullable', 'string', 'max:255'],
            ]),

            'live_chat' => $request->validate([
                'welcome_message' => ['nullable', 'string', 'max:200'],
                'agent_name' => ['nullable', 'string', 'max:100'],
                'agent_avatar' => ['nullable', 'string', 'max:500'],
                'working_hours' => ['nullable', 'string', 'max:100'],
                'offline_message' => ['nullable', 'string', 'max:300'],
            ]),

            default => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
            ]),
        };
    }
}

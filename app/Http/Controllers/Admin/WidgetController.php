<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    public function index()
    {
        $widgets = Widget::orderBy('sort_order')->get();

        return view('admin.widget.index', compact('widgets'));
    }

    public function edit(Widget $widget)
    {
        $view = 'admin.widget.edit-' . str_replace('_', '-', $widget->key);

        if (!view()->exists($view)) {
            return redirect()
                ->route('admin.widgets.index')
                ->withErrors(['error' => 'Edit page for "' . $widget->key . '" is not yet available.']);
        }

        return view($view, compact('widget'));
    }

    public function update(Request $request, Widget $widget)
    {
        $data = $this->validateByKey($request, $widget->key);

        $data = $this->handleUploads($request, $widget, $data);

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

    protected function handleUploads(Request $request, Widget $widget, array $data): array
    {
        $folder = 'widgets/' . $widget->key;
        $existing = $widget->settings ?? [];

        if ($widget->key === 'sign_hero') {
            foreach (['main_image', 'image_2', 'image_3'] as $key) {
                $oldImage = $existing[$key] ?? null;
                $fileKey = $key . '_file';

                if ($request->hasFile($fileKey)) {
                    if ($oldImage && !str_starts_with($oldImage, 'http')) {
                        Storage::disk('public')->delete($oldImage);
                    }
                    $data[$key] = $request->file($fileKey)->store($folder, 'public');
                } else {
                    $data[$key] = !empty($data[$key]) ? $data[$key] : $oldImage;
                }

                unset($data[$fileKey]);
                unset($data[$key . '_preview']);
            }
        }

        if ($widget->key === 'steps' && isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $index => $item) {
                $oldImage = $existing['items'][$index]['image'] ?? null;

                if ($request->hasFile("items.{$index}.image_file")) {
                    if ($oldImage && !str_starts_with($oldImage, 'http')) {
                        Storage::disk('public')->delete($oldImage);
                    }
                    $data['items'][$index]['image'] = $request->file("items.{$index}.image_file")->store($folder, 'public');
                } elseif (!empty($item['image'])) {
                    $data['items'][$index]['image'] = $item['image'];
                } else {
                    $data['items'][$index]['image'] = $oldImage;
                }

                unset($data['items'][$index]['image_file']);
                unset($data['items'][$index]['image_preview']);
            }
        }

        if ($widget->key === 'build_or_upload') {
            foreach (['build_card', 'upload_card'] as $cardKey) {
                if (!isset($data[$cardKey]) || !is_array($data[$cardKey])) {
                    continue;
                }

                $oldImage = $existing[$cardKey]['image'] ?? null;

                if ($request->hasFile("{$cardKey}.image_file")) {
                    if ($oldImage && !str_starts_with($oldImage, 'http')) {
                        Storage::disk('public')->delete($oldImage);
                    }
                    $data[$cardKey]['image'] = $request->file("{$cardKey}.image_file")->store($folder, 'public');
                } elseif (!empty($data[$cardKey]['image'])) {
                    $data[$cardKey]['image'] = $data[$cardKey]['image'];
                } else {
                    $data[$cardKey]['image'] = $oldImage ?? '';
                }

                unset($data[$cardKey]['image_file']);
                unset($data[$cardKey]['image_preview']);
            }
        }

        return $data;
    }

    protected function validateByKey(Request $request, string $key): array
    {
        return match ($key) {
            'hero', 'slider_mid' => $request->validate([
                'location' => ['required', 'string', 'max:100'],
            ]),

            'sign_hero' => $request->validate([
                'eyebrow' => ['nullable', 'string', 'max:100'],
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:300'],
                'main_image' => ['nullable', 'string', 'max:500'],
                'main_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'image_2' => ['nullable', 'string', 'max:500'],
                'image_2_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'image_3' => ['nullable', 'string', 'max:500'],
                'image_3_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'step1_text' => ['nullable', 'string', 'max:100'],
                'step2_text' => ['nullable', 'string', 'max:100'],
                'step3_text' => ['nullable', 'string', 'max:100'],
            ]),

            'steps' => $request->validate([
                'eyebrow' => ['nullable', 'string', 'max:100'],
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'button_text' => ['nullable', 'string', 'max:50'],
                'button_url' => ['nullable', 'string', 'max:255'],
                'items' => ['nullable', 'array', 'min:4', 'max:5'],
                'items.*.number' => ['nullable', 'string', 'max:10'],
                'items.*.title' => ['nullable', 'string', 'max:100'],
                'items.*.description' => ['nullable', 'string', 'max:500'],
                'items.*.link_text' => ['nullable', 'string', 'max:50'],
                'items.*.link_url' => ['nullable', 'string', 'max:255'],
                'items.*.image' => ['nullable', 'string', 'max:500'],
                'items.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ]),

            'build_or_upload' => $request->validate([
                'eyebrow' => ['nullable', 'string', 'max:100'],
                'title' => ['nullable', 'string', 'max:200'],
                'subtitle' => ['nullable', 'string', 'max:500'],
                'build_card' => ['nullable', 'array'],
                'build_card.badge' => ['nullable', 'string', 'max:30'],
                'build_card.title' => ['nullable', 'string', 'max:100'],
                'build_card.description' => ['nullable', 'string', 'max:500'],
                'build_card.button_text' => ['nullable', 'string', 'max:50'],
                'build_card.button_url' => ['nullable', 'string', 'max:255'],
                'build_card.image' => ['nullable', 'string', 'max:500'],
                'build_card.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'upload_card' => ['nullable', 'array'],
                'upload_card.badge' => ['nullable', 'string', 'max:30'],
                'upload_card.title' => ['nullable', 'string', 'max:100'],
                'upload_card.description' => ['nullable', 'string', 'max:500'],
                'upload_card.button_text' => ['nullable', 'string', 'max:50'],
                'upload_card.button_url' => ['nullable', 'string', 'max:255'],
                'upload_card.image' => ['nullable', 'string', 'max:500'],
                'upload_card.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'info_card' => ['nullable', 'array'],
                'info_card.badge' => ['nullable', 'string', 'max:30'],
                'info_card.number' => ['nullable', 'string', 'max:10'],
                'info_card.title' => ['nullable', 'string', 'max:100'],
                'info_card.description' => ['nullable', 'string', 'max:500'],
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

            'special_films_story' => $request->validate([
                'stats' => ['nullable', 'array', 'max:8'],
                'stats.*.value' => ['nullable', 'string', 'max:30'],
                'stats.*.sub' => ['nullable', 'string', 'max:30'],
                'stats.*.label' => ['nullable', 'string', 'max:60'],
                'story_eyebrow' => ['nullable', 'string', 'max:100'],
                'story_title' => ['nullable', 'string', 'max:200'],
                'steps' => ['nullable', 'array', 'max:10'],
                'steps.*.title' => ['nullable', 'string', 'max:150'],
                'steps.*.description' => ['nullable', 'string', 'max:2000'],
            ]),

            'special_films_faq' => $request->validate([
                'eyebrow' => ['nullable', 'string', 'max:150'],
                'title' => ['nullable', 'string', 'max:200'],
                'items' => ['nullable', 'array', 'max:20'],
                'items.*.question' => ['nullable', 'string', 'max:250'],
                'items.*.answer' => ['nullable', 'string', 'max:2000'],
            ]),

            'admin_ai_chat' => $request->validate([
                'enabled' => ['nullable', 'boolean'],
                'ai_url' => ['nullable', 'string', 'max:500'],
                'button_label' => ['nullable', 'string', 'max:60'],
                'popup_width' => ['nullable', 'integer', 'min:300', 'max:1200'],
                'popup_height' => ['nullable', 'integer', 'min:400', 'max:1400'],
                'position' => ['nullable', 'in:bottom-right,bottom-left'],
            ]),

            default => $request->validate([
                'title' => ['nullable', 'string', 'max:200'],
            ]),
        };
    }
}
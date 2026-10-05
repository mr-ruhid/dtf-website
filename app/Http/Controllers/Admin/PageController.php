<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $staticPages = Page::static()->orderBy('sort_order')->get();
        $customPages = Page::custom()->latest()->get();

        return view('admin.page.index', compact('staticPages', 'customPages'));
    }

    public function create()
    {
        return view('admin.page.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['type'] = 'custom';
        $data['is_locked'] = false;
        $data['status'] = $request->boolean('status', true);
        $data['show_in_footer'] = $request->boolean('show_in_footer');
        $data['show_in_header'] = $request->boolean('show_in_header');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('status', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.page.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $this->validateData($request, $page->id);

        if (!$page->is_locked) {
            $data['status'] = $request->boolean('status');
            $data['show_in_footer'] = $request->boolean('show_in_footer');
            $data['show_in_header'] = $request->boolean('show_in_header');
            $data['sort_order'] = $data['sort_order'] ?? 0;
        } else {
            $data['status'] = $request->boolean('status', true);
            $data['show_in_footer'] = $request->boolean('show_in_footer');
            $data['show_in_header'] = $request->boolean('show_in_header');
            $data['sort_order'] = $page->sort_order;
        }

        if ($request->hasFile('image')) {
            if ($page->image && !str_starts_with($page->image, 'http')) {
                Storage::disk('public')->delete($page->image);
            }
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        $page->update($data);

        return back()->with('status', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        if ($page->is_locked) {
            return back()->withErrors(['error' => 'This page is locked and cannot be deleted.']);
        }

        if ($page->image && !str_starts_with($page->image, 'http')) {
            Storage::disk('public')->delete($page->image);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted successfully.');
    }

    public function toggleStatus(Page $page)
    {
        if ($page->is_locked && $page->key === 'home') {
            return back()->withErrors(['error' => 'Home page cannot be deactivated.']);
        }

        $page->update(['status' => !$page->status]);

        return back()->with('status', 'Page status updated.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:pages,slug' . ($ignoreId ? ',' . $ignoreId : '')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'show_in_footer' => ['nullable', 'boolean'],
            'show_in_header' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:300'],
        ]);
    }
}

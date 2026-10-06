<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->orderBy('id')->get();
        $pages = Page::where('status', 1)->orderBy('sort_order')->get();

        return view('admin.menu.index', compact('menus', 'pages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:menus,slug'],
            'location' => ['required', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            $base = $data['slug'];
            $i = 1;
            while (Menu::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $base . '-' . $i++;
            }
        }

        $data['status'] = $request->boolean('status', true);

        $menu = Menu::create($data);

        return redirect()->route('admin.menus.show', $menu)->with('status', 'Menu created successfully.');
    }

    public function show(Menu $menu)
    {
        $menu->load(['items' => function ($q) {
            $q->whereNull('parent_id')->orderBy('sort_order')->with(['children' => function ($c) {
                $c->orderBy('sort_order');
            }]);
        }]);

        $pages = Page::where('status', 1)->orderBy('sort_order')->get();

        return view('admin.menu.show', compact('menu', 'pages'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:menus,slug,' . $menu->id],
            'location' => ['required', 'string', 'max:50'],
            'status' => ['nullable', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = $menu->slug;
        }

        $data['status'] = $request->boolean('status');

        $menu->update($data);

        return back()->with('status', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('status', 'Menu deleted successfully.');
    }

    public function toggle(Menu $menu)
    {
        $menu->update(['status' => !$menu->status]);

        return back()->with('status', 'Menu status updated.');
    }

    public function storeItem(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'target' => ['nullable', 'in:_self,_blank'],
            'icon' => ['nullable', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['menu_id'] = $menu->id;
        $data['target'] = $data['target'] ?? '_self';
        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? (MenuItem::where('menu_id', $menu->id)->max('sort_order') + 1);

        if (!empty($data['parent_id'])) {
            $parent = MenuItem::find($data['parent_id']);
            if (!$parent || $parent->menu_id !== $menu->id) {
                return back()->withErrors(['parent_id' => 'Invalid parent.']);
            }
        }

        MenuItem::create($data);

        Menu::clearCache($menu->slug);

        return back()->with('status', 'Item added successfully.');
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        if ($item->menu_id !== $menu->id) {
            abort(404);
        }

        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'target' => ['nullable', 'in:_self,_blank'],
            'icon' => ['nullable', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['target'] = $data['target'] ?? '_self';
        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if (!empty($data['parent_id'])) {
            if ($data['parent_id'] == $item->id) {
                return back()->withErrors(['parent_id' => 'Item cannot be its own parent.']);
            }
            $parent = MenuItem::find($data['parent_id']);
            if (!$parent || $parent->menu_id !== $menu->id) {
                return back()->withErrors(['parent_id' => 'Invalid parent.']);
            }
        } else {
            $data['parent_id'] = null;
        }

        $item->update($data);

        Menu::clearCache($menu->slug);

        return back()->with('status', 'Item updated successfully.');
    }

    public function destroyItem(Menu $menu, MenuItem $item)
    {
        if ($item->menu_id !== $menu->id) {
            abort(404);
        }

        foreach ($item->children as $child) {
            $child->delete();
        }

        $item->delete();

        Menu::clearCache($menu->slug);

        return back()->with('status', 'Item deleted successfully.');
    }

    public function reorder(Request $request, Menu $menu)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*.id' => ['required', 'integer'],
            'order.*.parent_id' => ['nullable', 'integer'],
        ]);

        foreach ($request->input('order') as $index => $row) {
            MenuItem::where('id', $row['id'])
                ->where('menu_id', $menu->id)
                ->update([
                    'sort_order' => $index + 1,
                    'parent_id' => $row['parent_id'] ?? null,
                ]);
        }

        Menu::clearCache($menu->slug);

        return response()->json(['success' => true]);
    }

    public function toggleItem(Menu $menu, MenuItem $item)
    {
        if ($item->menu_id !== $menu->id) {
            abort(404);
        }

        $item->update(['status' => !$item->status]);

        Menu::clearCache($menu->slug);

        return back()->with('status', 'Item status updated.');
    }
}

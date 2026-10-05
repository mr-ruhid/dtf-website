<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.gallery.index', compact('items'));
    }

    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'type' => ['required', 'in:image,video'],
            'image' => ['required_if:type,image', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['required_if:type,video', 'nullable', 'file', 'mimes:mp4,mov,webm', 'max:102400'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'title' => $data['title'] ?? null,
            'type' => $data['type'],
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $request->boolean('status'),
        ];

        if ($data['type'] === 'image' && $request->hasFile('image')) {
            $payload['image'] = $request->file('image')->store('gallery', 'public');
        }

        if ($data['type'] === 'video' && $request->hasFile('video')) {
            $result = $cloudinary->uploadVideo($request->file('video')->getRealPath(), 'gallery');
            $payload['video_url'] = $result['url'];
            $payload['video_public_id'] = $result['public_id'];
            $payload['video_thumbnail'] = $result['thumbnail'];
        }

        GalleryItem::create($payload);

        return back()->with('status', 'Item added successfully.');
    }

    public function update(Request $request, GalleryItem $item, CloudinaryService $cloudinary)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:102400'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'title' => $data['title'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $request->boolean('status'),
        ];

        if ($item->type === 'image' && $request->hasFile('image')) {
            if ($item->image && !str_starts_with($item->image, 'http')) {
                Storage::disk('public')->delete($item->image);
            }
            $payload['image'] = $request->file('image')->store('gallery', 'public');
        }

        if ($item->type === 'video' && $request->hasFile('video')) {
            if ($item->video_public_id) {
                $cloudinary->deleteVideo($item->video_public_id);
            }
            $result = $cloudinary->uploadVideo($request->file('video')->getRealPath(), 'gallery');
            $payload['video_url'] = $result['url'];
            $payload['video_public_id'] = $result['public_id'];
            $payload['video_thumbnail'] = $result['thumbnail'];
        }

        $item->update($payload);

        return back()->with('status', 'Item updated successfully.');
    }

    public function destroy(GalleryItem $item, CloudinaryService $cloudinary)
    {
        if ($item->type === 'image' && $item->image && !str_starts_with($item->image, 'http')) {
            Storage::disk('public')->delete($item->image);
        }

        if ($item->type === 'video' && $item->video_public_id) {
            $cloudinary->deleteVideo($item->video_public_id);
        }

        $item->delete();

        return back()->with('status', 'Item deleted successfully.');
    }

    public function toggleStatus(GalleryItem $item)
    {
        $item->update(['status' => !$item->status]);
        return back()->with('status', 'Status updated.');
    }
}

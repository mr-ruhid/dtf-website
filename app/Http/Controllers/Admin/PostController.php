<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['status'] = $request->boolean('status');
        $data['published_at'] = $data['status'] ? now() : null;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.blog.index')->with('status', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validateData($request, $post->id);

        $data['status'] = $request->boolean('status');

        if ($data['status'] && !$post->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($post->image && !str_starts_with($post->image, 'http')) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.blog.index')->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->image && !str_starts_with($post->image, 'http')) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Post deleted successfully.');
    }

    public function toggleStatus(Post $post)
    {
        $post->update([
            'status' => !$post->status,
            'published_at' => !$post->status && !$post->published_at ? now() : $post->published_at,
        ]);

        return back()->with('status', 'Post status updated.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:posts,slug' . ($ignoreId ? ',' . $ignoreId : '')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['nullable', 'boolean'],
        ]);
    }
}

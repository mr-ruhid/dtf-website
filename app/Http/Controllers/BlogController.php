<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 1)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('theme.rjshop-theme.staticpages.blog', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $related = Post::where('status', 1)
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('theme.rjshop-theme.staticpages.blog-show', compact('post', 'related'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\ProductModel;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        $urls[] = ['loc' => route('page.home'), 'priority' => '1.0', 'changefreq' => 'daily'];
        $urls[] = ['loc' => route('page.about'), 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => route('page.contact'), 'priority' => '0.8', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => route('page.faq'), 'priority' => '0.8', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => route('blog.index'), 'priority' => '0.8', 'changefreq' => 'daily'];
        $urls[] = ['loc' => route('gallery.index'), 'priority' => '0.5', 'changefreq' => 'monthly'];

        foreach (Post::where('status', 1)->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'weekly'
            ];
        }

        foreach (Product::where('status', 1)->get() as $product) {
            $urls[] = [
                'loc' => route('product.show', $product->slug),
                'lastmod' => $product->updated_at->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ];
        }

        foreach (ProductModel::where('status', 1)->get() as $model) {
            $urls[] = [
                'loc' => route('model.show', $model->slug),
                'lastmod' => $model->updated_at->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ];
        }

        return response()->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}

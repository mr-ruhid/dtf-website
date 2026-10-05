@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<section class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="max-w-3xl">
            <nav class="text-sm text-indigo-200 mb-3">
                <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $page->title }}</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-bold mb-3">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="text-indigo-100 text-lg">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
</section>

<section class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                @if($page->image_url)
                    <img src="{{ $page->image_url }}" alt="{{ $page->title }}" class="w-full rounded-xl mb-8 shadow-sm">
                @endif

                <article class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-indigo-600 prose-img:rounded-lg">
                    {!! $page->content !!}
                </article>
            </div>

            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-24">
                    <h3 class="font-semibold text-gray-900 mb-4">Need help?</h3>
                    <p class="text-sm text-gray-600 mb-4">Have a question about our products or services? Get in touch with our team.</p>
                    <a href="{{ url('contact-us') }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                        Contact Us
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

@push('styles')
<style>
.prose h2{font-size:1.5rem;font-weight:700;margin-top:1.5rem;margin-bottom:.75rem;}
.prose h3{font-size:1.25rem;font-weight:600;margin-top:1.25rem;margin-bottom:.5rem;}
.prose p{margin-bottom:1rem;line-height:1.75;}
.prose ul{list-style:disc;padding-left:1.5rem;margin-bottom:1rem;}
.prose ol{list-style:decimal;padding-left:1.5rem;margin-bottom:1rem;}
.prose li{margin-bottom:.25rem;}
</style>
@endpush

@endsection

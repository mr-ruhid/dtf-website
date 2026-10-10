@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Gallery — Our Work')
@section('meta_description', 'Browse our portfolio of printed designs, signs, apparel, and custom work.')
@section('meta_keywords', 'gallery, portfolio, work, designs, print, signs')

@push('styles')
<link rel="stylesheet" href="{{ asset('theme/rjshop-theme/css/gallery.css') }}">
@endpush

@section('content')

@php
    $galleryItemsForJs = $items->map(function ($item) {
        return [
            'id' => $item->id,
            'type' => $item->type,
            'title' => $item->title,
            'image_url' => $item->image_url,
            'video_url' => $item->video_url,
            'video_thumbnail' => $item->video_thumbnail_url,
        ];
    })->values()->all();
@endphp

<section class="rj-gl" x-data="galleryPage()">

    <div class="rj-gl-hero">
        <div class="rj-gl-hero-bg"></div>
        <div class="rj-gl-hero-inner">
            <div class="rj-gl-eyebrow">
                <span class="rj-gl-eyebrow-dot"></span>
                <span>Our Work</span>
            </div>
            <h1 class="rj-gl-title">Made. Printed. Delivered.</h1>
            <p class="rj-gl-sub">
                A look at the signs, transfers, apparel, and custom jobs that came through our shop.
                {{ $imageCount }} {{ $imageCount === 1 ? 'photo' : 'photos' }}
                @if($videoCount > 0)
                    · {{ $videoCount }} {{ $videoCount === 1 ? 'video' : 'videos' }}
                @endif
            </p>
        </div>
    </div>

    @if($items->count())
        <div class="rj-gl-wrap">
            <div class="rj-gl-grid" x-ref="grid">
                @foreach($items as $index => $item)
                    @php
                        $isVideo = $item->type === 'video';
                        $thumb = $isVideo ? $item->video_thumbnail_url : $item->image_url;
                    @endphp

                    <div class="rj-gl-item {{ $isVideo ? 'is-video' : '' }}"
                         @click="open({{ $index }})"
                         data-index="{{ $index }}">
                        <div class="rj-gl-item-img">
                            @if($thumb)
                                <img src="{{ $thumb }}"
                                     alt="{{ $item->title ?: 'Gallery item' }}"
                                     loading="lazy">
                            @else
                                <div class="rj-gl-item-empty">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            @endif
                        </div>

                        @if($isVideo)
                            <div class="rj-gl-item-play">
                                <span class="rj-gl-play-btn">
                                    <i class="fa-solid fa-play"></i>
                                </span>
                            </div>
                        @endif

                        @if($item->title)
                            <div class="rj-gl-item-overlay">
                                <p class="rj-gl-item-title">{{ $item->title }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="rj-gl-wrap">
            <div class="rj-gl-empty">
                <i class="fa-solid fa-images"></i>
                <p>No gallery items yet</p>
                <p class="rj-gl-empty-sub">Check back soon — we're adding new work regularly.</p>
            </div>
        </div>
    @endif

    {{-- ============ LIGHTBOX MODAL ============ --}}
    <div class="rj-gl-modal"
         x-show="isOpen"
         x-cloak
         x-transition:enter="rj-gl-modal-enter"
         x-transition:enter-start="rj-gl-modal-enter-start"
         x-transition:enter-end="rj-gl-modal-enter-end"
         x-transition:leave="rj-gl-modal-leave"
         x-transition:leave-start="rj-gl-modal-leave-start"
         x-transition:leave-end="rj-gl-modal-leave-end"
         @keydown.escape.window="close()">

        <div class="rj-gl-modal-backdrop" @click="close()"></div>

        <button type="button" class="rj-gl-modal-close" @click="close()" title="Close (Esc)">
            <i class="fa-solid fa-xmark"></i>
        </button>

        @if($items->count() > 1)
            <button type="button" class="rj-gl-modal-nav rj-gl-nav-prev"
                    @click.stop="prev()"
                    :disabled="currentIndex === 0"
                    title="Previous (←)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button type="button" class="rj-gl-modal-nav rj-gl-nav-next"
                    @click.stop="next()"
                    :disabled="currentIndex >= {{ $items->count() }} - 1"
                    title="Next (→)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        @endif

        <div class="rj-gl-modal-stage">
            <template x-if="current && current.type === 'image'">
                <img :src="current.image_url"
                     :alt="current.title || 'Gallery image'"
                     class="rj-gl-modal-img">
            </template>

            <template x-if="current && current.type === 'video'">
                <video :src="current.video_url"
                       controls
                       autoplay
                       playsinline
                       class="rj-gl-modal-video"
                       x-ref="videoEl"></video>
            </template>
        </div>

        <div class="rj-gl-modal-info" x-show="current && current.title">
            <p class="rj-gl-modal-title" x-text="current ? current.title : ''"></p>
            <p class="rj-gl-modal-counter">
                <span x-text="currentIndex + 1"></span> / {{ $items->count() }}
            </p>
        </div>

        @if($items->count() > 1)
            <div class="rj-gl-modal-thumbs">
                <template x-for="(item, idx) in items" :key="idx">
                    <button type="button"
                            class="rj-gl-modal-thumb"
                            :class="{ 'is-active': idx === currentIndex }"
                            @click.stop="goTo(idx)">
                        <img :src="item.type === 'video' ? item.video_thumbnail : item.image_url" :alt="item.title || ''">
                        <template x-if="item.type === 'video'">
                            <span class="rj-gl-modal-thumb-play">
                                <i class="fa-solid fa-play"></i>
                            </span>
                        </template>
                    </button>
                </template>
            </div>
        @endif

    </div>

</section>

<script>
function galleryPage() {
    return {
        items: @json($galleryItemsForJs),

        isOpen: false,
        currentIndex: 0,

        get current() {
            return this.items[this.currentIndex] || null;
        },

        init() {
            document.addEventListener('keydown', (e) => {
                if (!this.isOpen) return;

                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.prev();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.next();
                }
            });
        },

        open(index) {
            this.currentIndex = index;
            this.isOpen = true;
            this.lockBody(true);
        },

        close() {
            this.pauseVideo();
            this.isOpen = false;
            this.lockBody(false);
        },

        prev() {
            if (this.currentIndex <= 0) return;
            this.pauseVideo();
            this.currentIndex--;
        },

        next() {
            if (this.currentIndex >= this.items.length - 1) return;
            this.pauseVideo();
            this.currentIndex++;
        },

        goTo(index) {
            if (index < 0 || index >= this.items.length) return;
            this.pauseVideo();
            this.currentIndex = index;
        },

        pauseVideo() {
            const video = this.$refs.videoEl;
            if (video) {
                try { video.pause(); } catch (e) { /* ignore */ }
            }
        },

        lockBody(lock) {
            document.body.style.overflow = lock ? 'hidden' : '';
        }
    };
}
</script>

@endsection
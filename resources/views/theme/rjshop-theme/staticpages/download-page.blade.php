@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Download Files — ' . $order->order_number)
@section('meta_description', 'Download your order artwork files')
@section('meta_keywords', 'download, artwork, order')

@section('content')

<section class="rj-dl-hero">
    <div class="rj-dl-bg"></div>
    <div class="rj-dl-inner">

        <div class="rj-dl-head">
            <div class="rj-dl-eyebrow">
                <span class="rj-dl-dot"></span>
                <span>Order Files</span>
            </div>

            <h1 class="rj-dl-title">Download your artwork</h1>

            <p class="rj-dl-sub">
                Order <strong class="rj-dl-mono">{{ $order->order_number }}</strong>
                · {{ $order->created_at->format('d M Y') }}
                · {{ $totalFiles }} {{ $totalFiles === 1 ? 'file' : 'files' }}
            </p>
        </div>

        <div class="rj-dl-expires">
            <i class="fa-solid fa-clock"></i>
            <span>
                Available until
                <strong>{{ $order->download_expires_at->format('d M Y, H:i') }}</strong>
                ({{ now()->diffInDays($order->download_expires_at) }} {{ now()->diffInDays($order->download_expires_at) === 1 ? 'day' : 'days' }} left)
            </span>
        </div>

        @if($totalFiles > 0)
            <div class="rj-dl-actions">
                <a href="{{ route('order.download.zip', ['token' => $order->tracking_token]) }}" class="rj-dl-btn-primary">
                    <i class="fa-solid fa-file-zipper"></i>
                    <span>Download all files (ZIP)</span>
                </a>
            </div>

            @foreach($groups as $group)
                <div class="rj-dl-group">
                    <div class="rj-dl-group-head">
                        <div class="rj-dl-group-info">
                            <h2 class="rj-dl-group-title">{{ $group['title'] }}</h2>
                            <p class="rj-dl-group-meta">
                                @if($group['size_label'])
                                    <span>{{ $group['size_label'] }}</span>
                                    <span class="rj-dl-sep">·</span>
                                @endif
                                <span>Qty {{ $group['qty'] }}</span>
                                <span class="rj-dl-sep">·</span>
                                <span>{{ count($group['files']) }} {{ count($group['files']) === 1 ? 'file' : 'files' }}</span>
                            </p>
                        </div>
                        <span class="rj-dl-folder">{{ $group['folder'] }}</span>
                    </div>

                    <div class="rj-dl-files">
                        @foreach($group['files'] as $file)
                            @php
                                $isPdf = !empty($file['mime']) && str_contains($file['mime'], 'pdf');
                                $isImg = !empty($file['mime']) && str_starts_with($file['mime'], 'image/');
                                $isComposite = $file['kind'] === 'composite';
                                $ext = strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'FILE');
                            @endphp

                            <div class="rj-dl-file {{ $isComposite ? 'is-composite' : '' }}">
                                <div class="rj-dl-file-icon">
                                    @if($isPdf)
                                        <i class="fa-solid fa-file-pdf"></i>
                                    @elseif($isImg)
                                        <i class="fa-solid fa-file-image"></i>
                                    @else
                                        <i class="fa-solid fa-file"></i>
                                    @endif
                                </div>

                                <div class="rj-dl-file-body">
                                    <p class="rj-dl-file-name" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                                    <p class="rj-dl-file-meta">
                                        @if($isComposite)
                                            <span class="rj-dl-tag rj-dl-tag-primary">Print file</span>
                                        @else
                                            <span class="rj-dl-tag">Source</span>
                                        @endif
                                        <span class="rj-dl-mono">{{ $ext }}</span>
                                        @if($file['size'])
                                            <span class="rj-dl-sep">·</span>
                                            <span class="rj-dl-mono">{{ $file['size'] }}</span>
                                        @endif
                                    </p>
                                </div>

                                <a href="{{ route('order.download.file', ['token' => $order->tracking_token, 'designId' => $file['id']]) }}"
                                   class="rj-dl-file-btn" title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        @else
            <div class="rj-dl-empty">
                <i class="fa-solid fa-folder-open"></i>
                <p>No files available for this order.</p>
            </div>
        @endif

        <div class="rj-dl-footer">
            <p class="rj-dl-footer-note">
                <i class="fa-solid fa-circle-info"></i>
                Save the files to your device. After the expiry date, the link will stop working.
            </p>

            <div class="rj-dl-footer-links">
                <a href="{{ url('/') }}" class="rj-dl-link">
                    <i class="fa-solid fa-house"></i> Home
                </a>
                @if($order->tracking_token)
                    <a href="{{ route('track.show', ['token' => $order->tracking_token]) }}" class="rj-dl-link">
                        <i class="fa-solid fa-location-dot"></i> Track order
                    </a>
                @endif
                <a href="{{ url('contact-us') }}" class="rj-dl-link">
                    <i class="fa-solid fa-comments"></i> Need help?
                </a>
            </div>
        </div>

    </div>
</section>

<style>
    .rj-dl-hero {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 3rem 0 5rem;
        overflow: hidden;
        min-height: 60vh;
    }
    @media (min-width: 768px) { .rj-dl-hero { padding: 4rem 0 6rem; } }

    .rj-dl-bg {
        position: absolute; inset: 0; opacity: 0.03; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-dl-inner {
        position: relative;
        max-width: 56rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .rj-dl-head { margin-bottom: 1.5rem; }

    .rj-dl-eyebrow {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: #a5b4fc;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .rj-dl-dot {
        width: 6px; height: 6px;
        background: #6366f1;
        border-radius: 50%;
        box-shadow: 0 0 8px 2px rgba(99, 102, 241, 0.8);
    }

    .rj-dl-title {
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.025em;
        color: #fff;
        margin: 0 0 0.875rem;
    }

    .rj-dl-sub {
        font-size: 0.9375rem;
        color: #9ca3af;
        margin: 0;
    }

    .rj-dl-mono {
        font-family: ui-monospace, monospace;
        color: #c7d2fe;
    }

    .rj-dl-expires {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        padding: 0.875rem 1.125rem;
        background: rgba(251, 191, 36, 0.08);
        border: 1px solid rgba(251, 191, 36, 0.25);
        border-radius: 10px;
        font-size: 13px;
        color: #fcd34d;
        margin-bottom: 2rem;
    }
    .rj-dl-expires i { color: #fbbf24; flex-shrink: 0; }
    .rj-dl-expires strong { color: #fef3c7; }

    .rj-dl-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .rj-dl-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 1rem 1.75rem;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        text-decoration: none;
        border-radius: 9999px;
        font-size: 15px;
        font-weight: 700;
        transition: all 0.3s;
        box-shadow: 0 8px 24px -8px rgba(99, 102, 241, 0.6);
    }
    .rj-dl-btn-primary:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 12px 32px -8px rgba(99, 102, 241, 0.8);
    }

    .rj-dl-group {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        transition: border-color 0.3s;
    }
    .rj-dl-group:hover { border-color: rgba(99, 102, 241, 0.3); }

    .rj-dl-group-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .rj-dl-group-info { min-width: 0; }

    .rj-dl-group-title {
        font-size: 1.0625rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.375rem;
        letter-spacing: -0.01em;
    }

    .rj-dl-group-meta {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        align-items: center;
        font-family: ui-monospace, monospace;
    }

    .rj-dl-sep { color: #4b5563; }

    .rj-dl-folder {
        font-family: ui-monospace, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
        padding: 4px 10px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 6px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .rj-dl-files {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .rj-dl-file {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.75rem 0.875rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 10px;
        transition: all 0.25s;
    }
    .rj-dl-file:hover {
        background: rgba(99, 102, 241, 0.06);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .rj-dl-file.is-composite {
        background: rgba(99, 102, 241, 0.04);
        border-color: rgba(99, 102, 241, 0.2);
    }

    .rj-dl-file-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #a5b4fc;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 15px;
    }
    .rj-dl-file.is-composite .rj-dl-file-icon {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(168, 85, 247, 0.25));
        border-color: rgba(99, 102, 241, 0.4);
        color: #fff;
    }

    .rj-dl-file-body {
        flex: 1;
        min-width: 0;
    }

    .rj-dl-file-name {
        font-size: 13px;
        font-weight: 600;
        color: #e5e7eb;
        margin: 0 0 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .rj-dl-file-meta {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        flex-wrap: wrap;
    }

    .rj-dl-tag {
        display: inline-block;
        padding: 2px 7px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
        color: #9ca3af;
    }
    .rj-dl-tag-primary {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(168, 85, 247, 0.25));
        color: #c7d2fe;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .rj-dl-file-btn {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.25s;
        flex-shrink: 0;
    }
    .rj-dl-file-btn:hover {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        transform: scale(1.05);
    }

    .rj-dl-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 3rem 1rem;
        text-align: center;
        border: 1px dashed rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        color: #6b7280;
    }
    .rj-dl-empty i { font-size: 2rem; color: #4b5563; }
    .rj-dl-empty p { margin: 0; font-size: 14px; }

    .rj-dl-footer {
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .rj-dl-footer-note {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
        margin: 0 0 1.25rem;
    }
    .rj-dl-footer-note i { color: #818cf8; }

    .rj-dl-footer-links {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .rj-dl-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #9ca3af;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s;
    }
    .rj-dl-link:hover { color: #a5b4fc; }
    .rj-dl-link i { font-size: 11px; }

    @media (max-width: 640px) {
        .rj-dl-group-head { flex-direction: column; align-items: flex-start; }
        .rj-dl-file-name { font-size: 12px; }
    }
</style>

@endsection

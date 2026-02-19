@extends('layouts.app')
@section('title', 'Video Produk - Cosmetiqu')

@push('styles')
<style>
/* ====================================================
   VIDEO FEED - SHOPEE STYLE + COSMETIQU THEME
   ==================================================== */

.video-feed-container {
    background: #f8f9fa;
    min-height: calc(100vh - 140px);
    padding: 20px 0 40px;
}

.video-feed-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px;
}

.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

/* Video Card */
.video-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    position: relative;
}

.video-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(96,48,193,0.15);
}

/* Video Preview */
.video-preview {
    position: relative;
    width: 100%;
    padding-top: 133.33%; /* 3:4 aspect ratio like Shopee Video */
    background: #000;
    overflow: hidden;
}

.video-preview video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-preview img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Play overlay */
.video-play-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.video-card:hover .video-play-overlay {
    background: rgba(0,0,0,0.45);
}

.play-button {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.video-card:hover .play-button {
    transform: scale(1.1);
}

.play-button i {
    color: #6030C1;
    font-size: 24px;
    margin-left: 3px;
}

/* Video stats overlay */
.video-stats-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
}

.video-stat-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: white;
    font-size: 13px;
    margin-right: 12px;
}

.video-stat-item i {
    font-size: 14px;
}

/* Video Info */
.video-info {
    padding: 14px;
}

.video-title {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.video-creator {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.creator-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #6030C1;
}

.creator-name {
    font-size: 13px;
    color: #666;
    font-weight: 500;
}

/* Product Mini Card */
.video-product {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #F5F3FF;
    border-radius: 10px;
    margin-top: 8px;
    transition: background 0.2s;
}

.video-product:hover {
    background: #EDE9FE;
}

.product-thumb {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}

.product-info {
    flex: 1;
    min-width: 0;
}

.product-name {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a1a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-price {
    font-size: 14px;
    font-weight: 700;
    color: #6030C1;
    margin-top: 2px;
}

/* Filter tabs */
.video-filters {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e9ecef;
    overflow-x: auto;
    scrollbar-width: none;
}

.video-filters::-webkit-scrollbar {
    display: none;
}

.filter-tab {
    padding: 8px 20px;
    border-radius: 20px;
    border: 2px solid #e9ecef;
    background: white;
    font-size: 14px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.filter-tab:hover {
    border-color: #6030C1;
    color: #6030C1;
}

.filter-tab.active {
    background: #6030C1;
    border-color: #6030C1;
    color: white;
}

/* Upload button */
.upload-video-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6030C1, #8B5CF6);
    color: white;
    border: none;
    box-shadow: 0 4px 20px rgba(96,48,193,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    z-index: 100;
}

.upload-video-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 30px rgba(96,48,193,0.5);
}

.upload-video-btn i {
    font-size: 24px;
}

/* Empty state */
.empty-videos {
    text-align: center;
    padding: 80px 20px;
}

.empty-videos i {
    font-size: 80px;
    color: #e9ecef;
    margin-bottom: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .video-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .video-info {
        padding: 10px;
    }

    .video-title {
        font-size: 13px;
    }

    .upload-video-btn {
        width: 50px;
        height: 50px;
        bottom: 20px;
        right: 20px;
    }

    .upload-video-btn i {
        font-size: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="video-feed-container">
    <div class="video-feed-wrapper">

        {{-- Header --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-play-circle text-primary me-2"></i>
                        Shorts Video
                    </h2>
                    <p class="text-muted mb-0">Tonton video produk dari seller kami</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="video-filters">
            <button class="filter-tab active" data-filter="all">
                <i class="fas fa-fire me-1"></i>Semua
            </button>
            <button class="filter-tab" data-filter="trending">
                <i class="fas fa-chart-line me-1"></i>Trending
            </button>
            <button class="filter-tab" data-filter="newest">
                <i class="fas fa-clock me-1"></i>Terbaru
            </button>
            <button class="filter-tab" data-filter="most-liked">
                <i class="fas fa-heart me-1"></i>Terpopuler
            </button>
        </div>

        {{-- Video Grid --}}
        @if($videos->count() > 0)
            <div class="video-grid">
                @foreach($videos as $video)
                    <div class="video-card" onclick="window.location.href='{{ route('videos.show', $video->id) }}'">

                        {{-- Video Preview --}}
                        <div class="video-preview">
                            @if($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                            @else
                                <video src="{{ $video->video_url }}" preload="metadata"></video>
                            @endif

                            {{-- Play Overlay --}}
                            <div class="video-play-overlay">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>

                            {{-- Stats Overlay --}}
                            <div class="video-stats-overlay">
                                <span class="video-stat-item">
                                    <i class="fas fa-eye"></i>
                                    {{ $video->formatted_views }}
                                </span>
                                <span class="video-stat-item">
                                    <i class="fas fa-heart"></i>
                                    {{ $video->formatted_likes }}
                                </span>
                                @if($video->comments_count > 0)
                                    <span class="video-stat-item">
                                        <i class="fas fa-comment"></i>
                                        {{ $video->comments_count }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Video Info --}}
                        <div class="video-info">
                            @if($video->title)
                                <h6 class="video-title">{{ $video->title }}</h6>
                            @else
                                <h6 class="video-title">{{ $video->product->name }}</h6>
                            @endif

                            {{-- Creator --}}
                            <div class="video-creator">
                                @if($video->user->avatar)
                                    <img src="{{ $video->user->avatar_url }}"
                                         class="creator-avatar"
                                         alt="{{ $video->user->name }}">
                                @else
                                    <div class="creator-avatar"
                                         style="background: linear-gradient(135deg, #6030C1, #8B5CF6);
                                                display: flex; align-items: center; justify-content: center;
                                                color: white; font-size: 11px; font-weight: bold;">
                                        {{ strtoupper(substr($video->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="creator-name">{{ $video->user->name }}</span>
                            </div>

                            {{-- Product Mini Card --}}
                            <div class="video-product" onclick="event.stopPropagation(); window.location.href='{{ route('products.show', $video->product->slug) }}'">
                                @if($video->product->image)
                                    <img src="{{ $video->product->image_url }}"
                                         class="product-thumb"
                                         alt="{{ $video->product->name }}">
                                @else
                                    <div class="product-thumb"
                                         style="background: #f0f0f0; display: flex;
                                                align-items: center; justify-content: center;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif

                                <div class="product-info">
                                    <div class="product-name">{{ $video->product->name }}</div>
                                    <div class="product-price">{{ $video->product->formatted_price }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($videos->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $videos->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="empty-videos">
                <i class="fas fa-video"></i>
                <h4 class="fw-bold text-muted mb-2">Belum Ada Video</h4>
                <p class="text-muted">Belum ada video produk yang diupload.</p>

                @auth
                    @if(auth()->user()->isPenjual() || auth()->user()->isAdmin())
                        <a href="{{ route('videos.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-video me-2"></i>Upload Video Pertama
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>

@push('scripts'>
<script>
// Filter tabs
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        // TODO: Implement AJAX filtering
        console.log('Filter:', filter);
    });
});

// Prevent video card click when clicking product
document.querySelectorAll('.video-product').forEach(product => {
    product.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>
@endpush
@endsection

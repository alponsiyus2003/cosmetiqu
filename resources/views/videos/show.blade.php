@extends('layouts.app')
@section('title', ($video->title ?? $video->product->name) . ' - Video Produk')

@push('styles')
<style>
/* ====================================================
   VIDEO DETAIL PAGE - SHOPEE STYLE PLAYER
   ==================================================== */

.video-detail-container {
    background: #000;
    min-height: calc(100vh - 70px);
    position: relative;
}

.video-player-wrapper {
    position: relative;
    width: 100%;
    height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000;
}

.video-player {
    width: 100%;
    max-width: 100%;
    height: 100%;
    object-fit: contain;
}

/* Video Controls Overlay */
.video-controls-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom,
                rgba(0,0,0,0.5) 0%,
                transparent 20%,
                transparent 80%,
                rgba(0,0,0,0.7) 100%);
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
}

.video-player-wrapper:hover .video-controls-overlay {
    opacity: 1;
}

.video-controls-overlay.show {
    opacity: 1;
}

/* Right Sidebar Actions (Shopee style) */
.video-actions-sidebar {
    position: absolute;
    right: 20px;
    bottom: 100px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    z-index: 10;
}

.action-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    border: none;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.action-btn:hover {
    background: rgba(255,255,255,0.35);
    transform: scale(1.1);
}

.action-btn.active {
    background: rgba(239,68,68,0.9);
}

.action-btn i {
    font-size: 22px;
    margin-bottom: 2px;
}

.action-btn span {
    font-size: 11px;
    font-weight: 600;
}

/* Product Info Overlay (Bottom) */
.video-product-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 100%);
    color: white;
    z-index: 10;
    max-width: 600px;
}

.video-creator-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.creator-avatar-large {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 3px solid white;
    object-fit: cover;
}

.creator-details h6 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
}

.creator-details p {
    margin: 0;
    font-size: 13px;
    opacity: 0.8;
}

.video-description {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 16px;
    max-height: 60px;
    overflow: hidden;
}

.video-product-card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.video-product-card:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.product-thumb-large {
    width: 70px;
    height: 70px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
}

.product-details-inline {
    flex: 1;
}

.product-details-inline h5 {
    font-size: 15px;
    font-weight: 600;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.product-details-inline .price {
    font-size: 18px;
    font-weight: 700;
    color: #FCD34D;
}

.product-cta {
    background: #6030C1;
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.product-cta:hover {
    background: #4C1D95;
}

/* Comments Section */
.comments-section {
    background: white;
    padding: 24px;
    border-radius: 16px 16px 0 0;
}

.comments-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 20px;
}

.comment-input-wrapper {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 12px;
}

.comment-input-wrapper input {
    flex: 1;
    border: none;
    background: white;
    padding: 10px 16px;
    border-radius: 20px;
    font-size: 14px;
}

.comment-input-wrapper button {
    background: #6030C1;
    border: none;
    color: white;
    padding: 0 24px;
    border-radius: 20px;
    font-weight: 600;
}

.comment-item {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.comment-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    flex-shrink: 0;
    object-fit: cover;
}

.comment-content {
    flex: 1;
}

.comment-author {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.comment-time {
    font-size: 12px;
    color: #999;
}

.comment-text {
    font-size: 14px;
    color: #333;
    line-height: 1.5;
    margin-top: 4px;
}

/* Close button */
.close-video-btn {
    position: absolute;
    top: 20px;
    left: 20px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    z-index: 20;
    transition: all 0.2s;
}

.close-video-btn:hover {
    background: rgba(255,255,255,0.4);
    transform: scale(1.1);
}

/* Desktop: Side Panel */
@media (min-width: 992px) {
    .video-detail-container {
        display: flex;
    }

    .video-player-wrapper {
        flex: 1;
    }

    .video-side-panel {
        width: 400px;
        background: white;
        overflow-y: auto;
        height: calc(100vh - 70px);
    }

    .video-actions-sidebar {
        right: 420px;
    }

    .video-product-overlay {
        display: none;
    }
}

/* Mobile adjustments */
@media (max-width: 768px) {
    .video-actions-sidebar {
        right: 12px;
        bottom: 180px;
        gap: 16px;
    }

    .action-btn {
        width: 44px;
        height: 44px;
    }

    .action-btn i {
        font-size: 18px;
    }

    .action-btn span {
        font-size: 10px;
    }

    .video-product-overlay {
        padding: 16px;
    }
}
</style>
@endpush

@section('content')
<div class="video-detail-container">

    {{-- Video Player --}}
    <div class="video-player-wrapper" id="videoPlayerWrapper">

        {{-- Close Button --}}
        <a href="{{ route('videos.index') }}" class="close-video-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        {{-- Video --}}
        <video id="videoPlayer"
               class="video-player"
               src="{{ $video->video_url }}"
               controls
               autoplay
               loop
               playsinline></video>

        {{-- Controls Overlay --}}
        <div class="video-controls-overlay" id="controlsOverlay"></div>

        {{-- Right Sidebar Actions --}}
        <div class="video-actions-sidebar">

            {{-- Like --}}
            <button class="action-btn {{ $video->isLikedBy(auth()->id() ?? 0) ? 'active' : '' }}"
                    id="likeBtn"
                    onclick="toggleLike({{ $video->id }})">
                <i class="fas fa-heart"></i>
                <span id="likeCount">{{ $video->formatted_likes }}</span>
            </button>

            {{-- Comment --}}
            <button class="action-btn" onclick="scrollToComments()">
                <i class="fas fa-comment"></i>
                <span>{{ $video->comments->count() }}</span>
            </button>

            {{-- Share --}}
            <button class="action-btn" onclick="shareVideo()">
                <i class="fas fa-share"></i>
                <span>Bagikan</span>
            </button>

        </div>

        {{-- Product Info Overlay (Mobile) --}}
        <div class="video-product-overlay d-lg-none">

            {{-- Creator Info --}}
            <div class="video-creator-info">
                @if($video->user->avatar)
                    <img src="{{ $video->user->avatar_url }}"
                         class="creator-avatar-large"
                         alt="{{ $video->user->name }}">
                @else
                    <div class="creator-avatar-large"
                         style="background: linear-gradient(135deg, #6030C1, #8B5CF6);
                                display: flex; align-items: center; justify-content: center;
                                color: white; font-size: 18px; font-weight: bold;">
                        {{ strtoupper(substr($video->user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="creator-details">
                    <h6>{{ $video->user->name }}</h6>
                    <p>{{ $video->formatted_views }} views</p>
                </div>
            </div>

            {{-- Description --}}
            @if($video->description)
                <div class="video-description">{{ $video->description }}</div>
            @endif

            {{-- Product Card --}}
            <div class="video-product-card"
                 onclick="window.location.href='{{ route('products.show', $video->product->slug) }}'">
                @if($video->product->image)
                    <img src="{{ $video->product->image_url }}"
                         class="product-thumb-large"
                         alt="{{ $video->product->name }}">
                @else
                    <div class="product-thumb-large" style="background: #f0f0f0;"></div>
                @endif

                <div class="product-details-inline">
                    <h5>{{ $video->product->name }}</h5>
                    <div class="price">{{ $video->product->formatted_price }}</div>
                </div>

                <button class="product-cta">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Beli
                </button>
            </div>
        </div>
    </div>

    {{-- Side Panel (Desktop) --}}
    <div class="video-side-panel d-none d-lg-block">

        {{-- Product Info --}}
        <div class="p-4 border-bottom">
            <div class="d-flex align-items-center gap-3 mb-3">
                @if($video->user->avatar)
                    <img src="{{ $video->user->avatar_url }}"
                         class="creator-avatar-large"
                         alt="{{ $video->user->name }}">
                @else
                    <div class="creator-avatar-large"
                         style="background: linear-gradient(135deg, #6030C1, #8B5CF6);
                                display: flex; align-items: center; justify-content: center;
                                color: white; font-size: 18px; font-weight: bold;">
                        {{ strtoupper(substr($video->user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h6 class="mb-0 fw-bold">{{ $video->user->name }}</h6>
                    <small class="text-muted">{{ $video->formatted_views }} views</small>
                </div>
            </div>

            @if($video->title)
                <h5 class="fw-bold mb-2">{{ $video->title }}</h5>
            @endif

            @if($video->description)
                <p class="text-muted mb-0">{{ $video->description }}</p>
            @endif
        </div>

        {{-- Product Card --}}
        <div class="p-4 border-bottom">
            <h6 class="fw-bold mb-3">Produk di Video</h6>
            <div class="card border-0 shadow-sm">
                @if($video->product->image)
                    <img src="{{ $video->product->image_url }}"
                         class="card-img-top"
                         alt="{{ $video->product->name }}"
                         style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h6 class="fw-semibold mb-2">{{ $video->product->name }}</h6>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @for($s = 1; $s <= 5; $s++)
                            <i class="fas fa-star {{ $s <= round($video->product->average_rating) ? 'text-warning' : 'text-muted' }}"
                               style="font-size: 12px;"></i>
                        @endfor
                        <small class="text-muted">({{ $video->product->reviews_count }})</small>
                    </div>
                    <h4 class="text-primary fw-bold mb-3">{{ $video->product->formatted_price }}</h4>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $video->product->slug) }}"
                           class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>Lihat Produk
                        </a>
                        @auth
                            @if(auth()->user()->isPengguna())
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $video->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="comments-section" id="commentsSection">
            <h6 class="fw-bold mb-3">Komentar ({{ $video->comments->count() }})</h6>

            @auth
                {{-- Comment Input --}}
                <div class="comment-input-wrapper">
                    <input type="text"
                           id="commentInput"
                           placeholder="Tulis komentar..."
                           maxlength="500">
                    <button onclick="postComment({{ $video->id }})">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            @else
                <div class="alert alert-light text-center mb-3">
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login</a>
                    untuk berkomentar
                </div>
            @endauth

            {{-- Comments List --}}
            <div id="commentsList">
                @forelse($video->comments as $comment)
                    <div class="comment-item">
                        @if($comment->user->avatar)
                            <img src="{{ $comment->user->avatar_url }}"
                                 class="comment-avatar"
                                 alt="{{ $comment->user->name }}">
                        @else
                            <div class="comment-avatar"
                                 style="background: linear-gradient(135deg, #6030C1, #8B5CF6);
                                        display: flex; align-items: center; justify-content: center;
                                        color: white; font-size: 13px; font-weight: bold;">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="comment-content">
                            <div class="comment-author">
                                {{ $comment->user->name }}
                                <span class="comment-time">• {{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="comment-text">{{ $comment->comment }}</div>

                            @auth
                                <div class="mt-2">
                                    <button type="button"
                                            class="btn btn-link btn-sm p-0 text-primary"
                                            onclick="toggleReplyForm({{ $comment->id }})">
                                        <i class="fas fa-reply me-1"></i>Balas
                                    </button>
                                </div>
                                <div id="replyForm-{{ $comment->id }}" class="mt-2 d-none">
                                    <div class="d-flex gap-2">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="replyInput-{{ $comment->id }}"
                                               placeholder="Tulis balasan..."
                                               maxlength="1000">
                                        <button class="btn btn-sm btn-primary"
                                                onclick="postReply({{ $video->id }}, {{ $comment->id }})">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            @endauth

                            @if($comment->replies->count() > 0)
                                <div class="mt-3">
                                    @foreach($comment->replies as $reply)
                                        <div class="border-start border-2 ps-3 py-2 mb-2" style="border-color: #e9d5ff !important;">
                                            <div class="fw-semibold small">
                                                {{ $reply->user->name }}
                                                <span class="text-muted">• {{ $reply->role }}</span>
                                                <span class="comment-time">• {{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="comment-text mt-1">{{ $reply->reply }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted py-4">Belum ada komentar</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const likeUrl = '{{ route('videos.like', ['video' => $video->id]) }}';
const commentUrl = '{{ route('videos.comment', ['video' => $video->id]) }}';

// Like video
let isLiked = {{ $video->isLikedBy(auth()->id() ?? 0) ? 'true' : 'false' }};

function toggleLike(videoId) {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    fetch(likeUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById('likeBtn');
            const count = document.getElementById('likeCount');

            if (data.liked) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }

            count.textContent = data.formatted_likes;
        }
    });
}

// Post comment
function postComment(videoId) {
    const input = document.getElementById('commentInput');
    const comment = input.value.trim();

    if (!comment) return;

    fetch(commentUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ comment })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            location.reload(); // Reload untuk update komentar
        }
    });
}

// Scroll to comments
function scrollToComments() {
    const section = document.getElementById('commentsSection');
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

function toggleReplyForm(commentId) {
    const form = document.getElementById(`replyForm-${commentId}`);
    if (form) {
        form.classList.toggle('d-none');
    }
}

function postReply(videoId, commentId) {
    const input = document.getElementById(`replyInput-${commentId}`);
    const reply = input.value.trim();

    if (!reply) return;

    fetch('{{ route('videos.reply', ['video' => $video->id]) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ comment_id: commentId, reply })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            location.reload();
        }
    });
}

// Share video
function shareVideo() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $video->title ?? $video->product->name }}',
            text: 'Lihat video produk ini di Cosmetiqu!',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link berhasil disalin!');
    }
}

// Show/hide controls on interaction
let controlsTimeout;
const wrapper = document.getElementById('videoPlayerWrapper');
const overlay = document.getElementById('controlsOverlay');

wrapper.addEventListener('mousemove', () => {
    overlay.classList.add('show');
    clearTimeout(controlsTimeout);
    controlsTimeout = setTimeout(() => {
        overlay.classList.remove('show');
    }, 3000);
});
</script>
@endpush
@endsection

@extends('layouts.app')
@section('title', $product->name . ' - Cosmetiqu')

@push('styles')
<style>
/* ==============================================
   FIX UTAMA: sticky image TIDAK overlap navbar
   ============================================== */

/* Navbar di layouts.app biasanya ~60-70px.
   Tambah padding atas halaman supaya konten
   tidak langsung menempel navbar */
.page-content {
    padding-top: 24px;
    padding-bottom: 60px;
}

/* Sticky image: top harus >= tinggi navbar
   Ganti nilai ini jika navbar Anda berbeda tinggi */
.sticky-product-image {
    position: sticky;
    top: 75px;        /* sesuaikan dengan tinggi navbar */
    z-index: 10;      /* di bawah navbar (navbar biasanya z-index 1000+) */
    align-self: flex-start;
}

.product-img-box {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.product-img-box img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    display: block;
}

.product-img-placeholder {
    width: 100%;
    height: 420px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Rating stars */
.star-filled  { color: #ffc107; }
.star-empty   { color: #dee2e6; }

/* Review media grid */
.review-media-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.rm-thumb {
    position: relative;
    width: 90px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    flex-shrink: 0;
    transition: transform 0.18s, box-shadow 0.18s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.rm-thumb:hover {
    transform: scale(1.06);
    box-shadow: 0 6px 18px rgba(0,0,0,0.28);
}

.rm-thumb img,
.rm-thumb video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* hover overlay untuk image */
.rm-thumb .rm-hover {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.18s;
}
.rm-thumb:hover .rm-hover { background: rgba(0,0,0,0.32); }
.rm-thumb .rm-hover i { color: #fff; font-size: 1.3rem; opacity: 0; transition: opacity 0.18s; }
.rm-thumb:hover .rm-hover i { opacity: 1; }

/* video overlay selalu tampil */
.rm-thumb .rm-video-play {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
}

.rm-badge {
    position: absolute;
    bottom: 4px;
    left: 4px;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 3px;
    letter-spacing: 0.3px;
    pointer-events: none;
}

/* Review reply section */
.review-replies {
    margin-top: 12px;
    margin-left: 20px;
    padding-left: 14px;
    border-left: 3px solid #6030C1;
}
.reply-bubble {
    background: #F5F3FF;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 8px;
}
.reply-bubble:last-child { margin-bottom: 0; }

/* Rating bar */
.rbar-row   { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.rbar-label { width: 58px; font-size: 13px; white-space: nowrap; flex-shrink: 0; }
.rbar-track { flex: 1; height: 13px; background: #e9ecef; border-radius: 10px; overflow: hidden; }
.rbar-fill  { height: 100%; background: #ffc107; border-radius: 10px; }
.rbar-count { width: 28px; text-align: right; font-size: 12px; color: #888; flex-shrink: 0; }

/* Individual review separator */
.review-row {
    padding: 20px 0;
    border-bottom: 1px solid #f0f0f0;
}
.review-row:last-child { border-bottom: none; padding-bottom: 0; }
</style>
@endpush

@section('content')
<div class="page-content">
<div class="container">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a>
            </li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">
                {{ $product->name }}
            </li>
        </ol>
    </nav>

    {{-- ===================================================
         PRODUCT DETAIL
         =================================================== --}}
    <div class="row g-4 mb-5">

        {{-- Kolom Gambar --}}
        <div class="col-md-5">
            <div class="sticky-product-image">
                <div class="product-img-box">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <div class="product-img-placeholder">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Info --}}
        <div class="col-md-7">

            <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
            <h2 class="fw-bold mb-2">{{ $product->name }}</h2>

            <p class="text-muted mb-1">
                <i class="fas fa-store me-2"></i>Dijual oleh: <strong>{{ $product->seller->name }}</strong>
            </p>
            @if(!empty($product->brand))
                <p class="text-muted mb-2">
                    <i class="fas fa-tag me-2"></i>Brand: <strong>{{ $product->brand }}</strong>
                </p>
            @endif

            {{-- Rating --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                @for($s = 1; $s <= 5; $s++)
                    <i class="fas fa-star {{ $s <= round($product->average_rating) ? 'star-filled' : 'star-empty' }}"></i>
                @endfor
                <span class="fw-semibold">{{ number_format($product->average_rating, 1) }}</span>
                <span class="text-muted">({{ $product->reviews_count }} ulasan)</span>
                @if($product->reviews_count > 0)
                    <a href="#reviews-section" class="small text-primary text-decoration-none">Lihat semua</a>
                @endif
            </div>

            <h3 class="text-primary fw-bold mb-4">{{ $product->formatted_price }}</h3>

            {{-- Deskripsi --}}
            <div class="bg-light rounded-3 p-3 mb-4">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-file-alt me-2 text-primary"></i>Deskripsi Produk
                </h6>
                <p class="text-muted mb-0 small lh-lg">{{ $product->description }}</p>
            </div>

            {{-- Stok & Kategori --}}
            <div class="bg-light rounded-3 p-3 mb-4">
                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Stok Tersedia</small>
                        <span class="badge fs-6 bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                            {{ $product->stock }} unit
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Kategori</small>
                        <span class="fw-semibold">{{ $product->category->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Add to Cart --}}
            @auth
                @if(auth()->user()->isPengguna())
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Jumlah</label>
                                <input type="number" name="quantity" class="form-control"
                                       style="max-width: 120px;"
                                       value="1" min="1" max="{{ $product->stock }}" required>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-shopping-cart me-2"></i>Lihat Keranjang
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>Produk ini sedang habis stok.
                        </div>
                    @endif
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
                    </div>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <a href="{{ route('login') }}" class="alert-link fw-bold">Login</a> untuk membeli produk ini.
                </div>
            @endauth

        </div>{{-- /col info --}}
    </div>{{-- /row product --}}


    {{-- ===================================================
         REVIEWS SECTION
         =================================================== --}}
    <div id="reviews-section">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-star text-warning me-2"></i>
                    Ulasan Produk
                    <span class="badge bg-primary ms-1">{{ $product->reviews_count }}</span>
                </h5>
            </div>
            <div class="card-body p-4">

                @if($product->approvedReviews->count() > 0)

                    {{-- Rating Summary --}}
                    <div class="row align-items-center mb-4 pb-4 border-bottom">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="display-4 fw-bold text-primary lh-1 mb-1">
                                {{ number_format($product->average_rating, 1) }}
                            </div>
                            <div class="mb-1">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="fas fa-star {{ $s <= round($product->average_rating) ? 'star-filled' : 'star-empty' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">{{ $product->reviews_count }} ulasan</small>
                        </div>
                        <div class="col-md-9">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $cnt = $product->approvedReviews->where('rating', $i)->count();
                                    $pct = $product->reviews_count > 0
                                         ? round(($cnt / $product->reviews_count) * 100) : 0;
                                @endphp
                                <div class="rbar-row">
                                    <span class="rbar-label">
                                        {{ $i }} <i class="fas fa-star star-filled" style="font-size:11px;"></i>
                                    </span>
                                    <div class="rbar-track">
                                        <div class="rbar-fill" style="width: {{ $pct }}%;"></div>
                                    </div>
                                    <span class="rbar-count">{{ $cnt }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Individual Reviews --}}
                    @foreach($product->approvedReviews->sortByDesc('created_at') as $review)
                        <div class="review-row">

                            {{-- Reviewer --}}
                            <div class="d-flex align-items-start mb-2">
                                @if($review->user->avatar)
                                    <img src="{{ $review->user->avatar_url }}"
                                         class="rounded-circle me-3 flex-shrink-0 shadow-sm"
                                         style="width:46px;height:46px;object-fit:cover;"
                                         alt="{{ $review->user->name }}">
                                @else
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0 shadow-sm"
                                         style="width:46px;height:46px;font-size:1rem;
                                                background: linear-gradient(135deg,#6030C1,#8B5CF6);">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $review->user->name }}</h6>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                        {{-- Stars --}}
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= $review->rating ? 'star-filled' : 'star-empty' }}" style="font-size:13px;"></i>
                                        @endfor
                                        <span class="badge bg-warning text-dark">{{ $review->rating_label }}</span>
                                        @if($review->is_verified_purchase)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Verified Purchase
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        {{ $review->created_at->format('d M Y, H:i') }}
                                        &bull; {{ $review->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>

                            {{-- Komentar --}}
                            @if($review->comment)
                                <p class="mb-3 lh-lg">{{ $review->comment }}</p>
                            @endif

                            {{-- ============================================
                                 FOTO & VIDEO UNBOXING
                                 ============================================ --}}
                            @if($review->media->count() > 0)
                                <div class="mb-3">
                                    <p class="mb-2 small text-muted fw-semibold">
                                        <i class="fas fa-photo-video me-1 text-primary"></i>
                                        Foto & Video Unboxing
                                        <span class="badge bg-secondary ms-1">{{ $review->media->count() }}</span>
                                    </p>
                                    <div class="review-media-grid">
                                        @foreach($review->media as $media)
                                            @if($media->is_image)
                                                {{-- IMAGE --}}
                                                <div class="rm-thumb"
                                                     onclick="openMedia('{{ $media->url }}','image','{{ addslashes($review->user->name) }}')"
                                                     title="Klik untuk memperbesar">
                                                    <img src="{{ $media->url }}"
                                                         alt="foto review"
                                                         loading="lazy">
                                                    <div class="rm-hover">
                                                        <i class="fas fa-search-plus"></i>
                                                    </div>
                                                    <span class="rm-badge bg-info text-white">
                                                        <i class="fas fa-image me-1"></i>FOTO
                                                    </span>
                                                </div>
                                            @else
                                                {{-- VIDEO --}}
                                                <div class="rm-thumb"
                                                     onclick="openMedia('{{ $media->url }}','video','{{ addslashes($review->user->name) }}')"
                                                     title="Klik untuk memutar video">
                                                    <video src="{{ $media->url }}#t=0.001"
                                                           preload="metadata"
                                                           muted
                                                           playsinline></video>
                                                    <div class="rm-video-play">
                                                        <i class="fas fa-play-circle fa-2x text-white"></i>
                                                    </div>
                                                    <span class="rm-badge bg-danger text-white">
                                                        <i class="fas fa-video me-1"></i>VIDEO
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- ============================================
                                 BALASAN PENJUAL / ADMIN
                                 ============================================ --}}
                            @if($review->replies->count() > 0)
                                <div class="review-replies">
                                    @foreach($review->replies as $reply)
                                        <div class="reply-bubble">
                                            <div class="d-flex align-items-start">
                                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2 fw-bold flex-shrink-0"
                                                     style="width:32px;height:32px;font-size:0.78rem;
                                                            background: linear-gradient(135deg,#6030C1,#8B5CF6);">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center flex-wrap gap-1 mb-1">
                                                        <strong class="small">{{ $reply->user->name }}</strong>
                                                        <span class="badge bg-{{ $reply->role_badge }}"
                                                              style="font-size:10px;">
                                                            <i class="fas fa-{{ $reply->role == 'admin' ? 'shield-alt' : 'store' }} me-1"></i>
                                                            {{ $reply->role_label }}
                                                        </span>
                                                        <small class="text-muted">
                                                            &bull; {{ $reply->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                    <p class="mb-0 small">{{ $reply->reply }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>{{-- /review-row --}}
                    @endforeach

                @else
                    <div class="text-center py-5">
                        <i class="fas fa-star fa-4x mb-3" style="color:#e9ecef;"></i>
                        <h5 class="fw-bold text-muted">Belum Ada Ulasan</h5>
                        <p class="text-muted mb-4">Jadilah yang pertama memberikan ulasan!</p>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login untuk Memberi Ulasan
                            </a>
                        @endguest
                    </div>
                @endif

            </div>
        </div>
    </div>{{-- /reviews-section --}}


    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Produk Terkait</h4>
                <a href="{{ route('products.category', $product->category->slug) }}"
                   class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 shadow-sm border-0">
                            @if($related->image)
                                <img src="{{ $related->image_url }}"
                                     class="card-img-top"
                                     alt="{{ $related->name }}"
                                     style="height:170px;object-fit:cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height:170px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body p-3">
                                <h6 class="card-title fw-semibold small mb-1">
                                    {{ Str::limit($related->name, 45) }}
                                </h6>
                                <div class="mb-1">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fas fa-star {{ $s <= round($related->average_rating) ? 'star-filled' : 'star-empty' }}"
                                           style="font-size:11px;"></i>
                                    @endfor
                                    <small class="text-muted">({{ $related->reviews_count }})</small>
                                </div>
                                <p class="text-primary fw-bold mb-2 small">{{ $related->formatted_price }}</p>
                                <a href="{{ route('products.show', $related->slug) }}"
                                   class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>{{-- /container --}}
</div>{{-- /page-content --}}


{{-- ===================================================
     LIGHTBOX MODAL
     =================================================== --}}
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0 shadow-lg">
            <div class="modal-header border-0 px-4 pt-3 pb-2">
                <h6 class="modal-title text-white mb-0" id="mmTitle"></h6>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4 pt-0" id="mmBody"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
/* ---------- Sticky top auto-detect ---------- */
document.addEventListener('DOMContentLoaded', function () {
    // Ukur tinggi navbar yang sebenarnya
    const navbar = document.querySelector('nav.navbar, header nav, #navbar, .navbar');
    if (navbar) {
        const h = navbar.offsetHeight;
        const sticky = document.querySelector('.sticky-product-image');
        if (sticky) {
            sticky.style.top = (h + 12) + 'px'; // navbar height + 12px gap
        }
    }
});

/* ---------- Lightbox ---------- */
function openMedia(url, type, name) {
    document.getElementById('mmTitle').innerHTML =
        `<i class="fas fa-${type==='image'?'image':'video'} me-2"></i>${name} &mdash; ${type==='image'?'Foto':'Video'} Review`;

    document.getElementById('mmBody').innerHTML = type === 'image'
        ? `<img src="${url}" class="img-fluid rounded shadow" style="max-height:78vh;width:auto;max-width:100%;" alt="review">`
        : `<video src="${url}" class="w-100 rounded shadow" style="max-height:78vh;" controls autoplay playsinline></video>`;

    new bootstrap.Modal(document.getElementById('mediaModal')).show();
}

document.getElementById('mediaModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('mmBody').innerHTML   = '';
    document.getElementById('mmTitle').innerHTML  = '';
});
</script>
@endpush
@endsection

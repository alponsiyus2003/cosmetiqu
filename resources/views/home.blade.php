@extends('layouts.app')
@section('title', 'Cosmetiqu - Toko Kosmetik Online Terpercaya')
@section('content')

@if($carousels->count() > 0)
<!-- Carousel Section -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($carousels as $index => $carousel)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach($carousels as $index => $carousel)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ $carousel->image_url }}" class="d-block w-100" alt="{{ $carousel->title }}" style="height: 500px; object-fit: cover;">
                <div class="carousel-caption" style="background: rgba(0,0,0,0.5); padding: 30px; border-radius: 15px; bottom: 50px;">
                    <h2 class="fw-bold mb-3">{{ $carousel->title }}</h2>
                    @if($carousel->description)
                        <p class="lead mb-4">{{ $carousel->description }}</p>
                    @endif
                    @if($carousel->button_text && $carousel->button_link)
                        <a href="{{ $carousel->button_link }}" class="btn btn-primary btn-lg">
                            {{ $carousel->button_text }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($carousels->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    @endif
</div>
@else
<!-- Default Hero Section (jika tidak ada carousel) -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fade-in-up">
                <span class="badge bg-primary mb-3 fs-6">✨ Kecantikan Terbaik</span>
                <h1 class="display-4 fw-bold mb-4">Temukan <span class="text-primary">Kecantikan</span><br>Sejatimu</h1>
                <p class="lead mb-4 text-muted">Koleksi produk kosmetik terlengkap dengan harga terbaik. Dari skincare hingga makeup, semua ada di Cosmetiqu!</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Belanja Sekarang
                    </a>
                    @auth
                        @if(auth()->user()->isPengguna())
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i>Keranjang
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                                @endphp
                                @if($cartCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $cartCount }}</span>
                                @endif
                            </a>
                        @endif
                    @endauth
                </div>
                <div class="row mt-5">
                    <div class="col-4">
                        <h3 class="text-primary fw-bold mb-0">500+</h3>
                        <small class="text-muted">Produk</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-primary fw-bold mb-0">10K+</h3>
                        <small class="text-muted">Customer</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-primary fw-bold mb-0">4.9</h3>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center fade-in-up">
                <div class="position-relative">
                    <i class="fas fa-heart fa-10x text-primary" style="opacity: 0.1;"></i>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i class="fas fa-spray-can fa-5x text-primary mb-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Kategori Produk</h2>
        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('products.category', $category->slug) }}" class="text-decoration-none">
                        <div class="card text-center h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                @if($category->image)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid mb-2" style="height: 60px; object-fit: contain;">
                                @else
                                    <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                                @endif
                                <h6 class="card-title mb-1">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->products_count }} Produk</small>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12"><p class="text-center text-muted">Belum ada kategori.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Produk Terbaru</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Lihat Semua <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-6 col-md-6 col-lg-3 fade-in-up">
                    <div class="card h-100">
                        <div class="position-relative overflow-hidden">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                                @else
                                    <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </a>
                            @if($product->stock <= 10 && $product->stock > 0)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-warning"><i class="fas fa-fire"></i> Terbatas</span>
                            @elseif($product->stock == 0)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Habis</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                            <h6 class="card-title mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-2"><i class="fas fa-store"></i> {{ $product->seller->name }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary mb-0 fw-bold">{{ $product->formatted_price }}</h5>
                                <small class="text-muted">Stok: {{ $product->stock }}</small>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            @auth
                                @if(auth()->user()->isPengguna())
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-cart-plus me-2"></i>Tambah
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled><i class="fas fa-times-circle me-2"></i>Habis</button>
                                    @endif
                                @else
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-eye me-2"></i>Detail
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-2"></i>Login</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Belum ada produk tersedia.</div></div>
            @endforelse
        </div>
    </div>
</section>

@guest
<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4 fw-bold">Mulai Belanja Sekarang!</h2>
        <p class="lead mb-4">Daftar sekarang dan dapatkan pengalaman belanja kosmetik terbaik</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5"><i class="fas fa-user-plus me-2"></i>Daftar Gratis</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5"><i class="fas fa-sign-in-alt me-2"></i>Login</a>
        </div>
    </div>
</section>
@endguest

<!-- Why Choose Us Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Mengapa Memilih Cosmetiqu?</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="mb-3"><i class="fas fa-shield-alt fa-4x text-primary"></i></div>
                <h4 class="fw-bold">Produk Original</h4>
                <p class="text-muted">Semua produk dijamin 100% original dari supplier terpercaya.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3"><i class="fas fa-shipping-fast fa-4x text-primary"></i></div>
                <h4 class="fw-bold">Pengiriman Cepat</h4>
                <p class="text-muted">Pengiriman ke seluruh Indonesia dengan ekspedisi terpercaya.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3"><i class="fas fa-headset fa-4x text-primary"></i></div>
                <h4 class="fw-bold">Layanan 24/7</h4>
                <p class="text-muted">Customer service kami siap membantu Anda kapan saja.</p>
            </div>
        </div>
    </div>
</section>
@endsection

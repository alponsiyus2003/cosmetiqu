@extends('layouts.app')
@section('title', 'Semua Produk - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3">Semua Produk</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Cari Produk</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama produk..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="">Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3 fade-in-up">
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
            <div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Tidak ada produk ditemukan.</div></div>
        @endforelse
    </div>
    @if($products->hasPages())
        <div class="mt-5 d-flex justify-content-center">{{ $products->links() }}</div>
    @endif
</div>
@endsection

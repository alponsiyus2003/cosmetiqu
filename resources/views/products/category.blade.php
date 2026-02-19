@extends('layouts.app')

@section('title', $category->name . ' - Cosmetiqu')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-12">
            <h1>{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-muted">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    @else
                        <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-store"></i> {{ $product->seller->name }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0">{{ $product->formatted_price }}</h5>
                            <span class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-warning' }}">
                                Stok: {{ $product->stock }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada produk dalam kategori ini.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

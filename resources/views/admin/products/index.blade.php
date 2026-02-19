@extends('layouts.admin')

@section('title', 'Kelola Produk - Admin Cosmetiqu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-box"></i> Kelola Produk
    </h2>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.products.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Penjual</label>
                    <select name="seller" class="form-select">
                        <option value="">Semua Penjual</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}" {{ request('seller') == $seller->id ? 'selected' : '' }}>
                                {{ $seller->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cari Produk</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama produk..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Grid -->
<div class="row">
    @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                @if($product->image)
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif

                <div class="card-body">
                    <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>

                    <h6 class="card-title">{{ Str::limit($product->name, 40) }}</h6>

                    <p class="text-muted small mb-2">
                        <i class="fas fa-store"></i> {{ $product->seller->name }}
                    </p>

                    @if($product->brand)
                        <p class="text-muted small mb-2">
                            <i class="fas fa-tag"></i> {{ $product->brand }}
                        </p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-primary">{{ $product->formatted_price }}</strong>
                        <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                            Stok: {{ $product->stock }}
                        </span>
                    </div>

                    <div>
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-info flex-fill">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box fa-5x text-muted mb-3"></i>
                    <h4>Tidak Ada Produk</h4>
                    <p class="text-muted">Belum ada produk yang ditambahkan oleh penjual.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($products->hasPages())
    <div class="mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
@endif
@endsection

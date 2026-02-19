@extends('layouts.penjual')

@section('title', 'Produk Saya - Penjual Cosmetiqu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-box"></i> Produk Saya
    </h2>
    <a href="{{ route('penjual.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('penjual.products.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
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

                <div class="col-md-6">
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
        <div class="col-md-4 col-lg-3 mb-4">
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
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('penjual.products.show', $product->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('penjual.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $product->id }})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <form id="delete-form-{{ $product->id }}" action="{{ route('penjual.products.destroy', $product->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box fa-5x text-muted mb-3"></i>
                    <h4>Belum Ada Produk</h4>
                    <p class="text-muted">Mulai dengan menambahkan produk pertama Anda.</p>
                    <a href="{{ route('penjual.products.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
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

@push('scripts')
<script>
function confirmDelete(productId) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini? Produk yang sudah dipesan tidak dapat dihapus.')) {
        document.getElementById('delete-form-' + productId).submit();
    }
}
</script>
@endpush

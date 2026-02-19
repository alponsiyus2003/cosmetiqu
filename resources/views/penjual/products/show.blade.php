@extends('layouts.penjual')

@section('title', 'Detail Produk - Penjual Cosmetiqu')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('penjual.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penjual.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2>
            <i class="fas fa-box"></i> {{ $product->name }}
        </h2>
        <div>
            <a href="{{ route('penjual.products.edit', $product->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('penjual.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Product Image & Info -->
    <div class="col-md-5">
        <div class="card mb-4">
            @if($product->image)
                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Link Produk</h6>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ route('products.show', $product->slug) }}" id="productUrl" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyUrl()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <small class="text-muted">Bagikan link ini kepada customer Anda</small>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">Detail Produk</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nama Produk</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $product->name }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Kategori</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                    </div>
                </div>

                @if($product->brand)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Brand</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->brand }}
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Harga</strong>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-primary mb-0">{{ $product->formatted_price }}</h4>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Stok</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                            {{ $product->stock }} unit
                        </span>
                        @if($product->stock <= 10 && $product->stock > 0)
                            <small class="text-warning ms-2">
                                <i class="fas fa-exclamation-triangle"></i> Stok menipis!
                            </small>
                        @elseif($product->stock == 0)
                            <small class="text-danger ms-2">
                                <i class="fas fa-times-circle"></i> Stok habis!
                            </small>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Status</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Ditambahkan</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $product->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Terakhir Diupdate</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $product->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">Deskripsi Produk</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $product->description }}</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                        <h3 class="mb-0">{{ $product->orderItems->count() }}</h3>
                        <small class="text-muted">Total Penjualan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-box fa-2x text-success mb-2"></i>
                        <h3 class="mb-0">{{ $product->orderItems->sum('quantity') }}</h3>
                        <small class="text-muted">Unit Terjual</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-money-bill-wave fa-2x text-warning mb-2"></i>
                        <h5 class="mb-0">Rp {{ number_format($product->orderItems->sum('subtotal'), 0, ',', '.') }}</h5>
                        <small class="text-muted">Total Pendapatan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-history"></i> Riwayat Penjualan ({{ $product->orderItems->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                @if($product->orderItems->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada penjualan untuk produk ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->orderItems as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('penjual.orders.show', $item->order->id) }}">
                                                #{{ $item->order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $item->order->user->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->formatted_price }}</td>
                                        <td>{{ $item->formatted_subtotal }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->order->status_badge }}">
                                                {{ ucfirst($item->order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyUrl() {
    const copyText = document.getElementById("productUrl");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    alert("Link produk berhasil disalin!");
}
</script>
@endpush

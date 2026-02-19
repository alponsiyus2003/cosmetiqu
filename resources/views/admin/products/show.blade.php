@extends('layouts.admin')

@section('title', 'Detail Produk - Admin Cosmetiqu')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2>
            <i class="fas fa-box"></i> {{ $product->name }}
        </h2>
        <div>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Produk
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
                <h6 class="mb-0">Informasi Penjual</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($product->seller->avatar)
                        <img src="{{ $product->seller->avatar_url }}" alt="Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            {{ strtoupper(substr($product->seller->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-0">{{ $product->seller->name }}</h6>
                        <small class="text-muted">{{ $product->seller->email }}</small>
                    </div>
                </div>

                @if($product->seller->phone)
                    <p class="mb-2">
                        <i class="fas fa-phone text-muted"></i> {{ $product->seller->phone }}
                    </p>
                @endif

                @if($product->seller->address)
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt text-muted"></i> {{ $product->seller->address }}
                    </p>
                @endif
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
                        <strong>Slug</strong>
                    </div>
                    <div class="col-md-8">
                        <code>{{ $product->slug }}</code>
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

        <!-- Order History -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-shopping-cart"></i> Riwayat Penjualan ({{ $product->orderItems->count() }})
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
                                            <a href="{{ route('admin.orders.show', $item->order->id) }}">
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

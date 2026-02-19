@extends('layouts.penjual')

@section('title', 'Penjual Dashboard - Cosmetiqu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard Penjual</h2>
    <div class="text-muted">
        <i class="fas fa-calendar"></i> {{ now()->format('d F Y') }}
    </div>
</div>

<!-- Welcome Card -->
<div class="card mb-4 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card-body text-white">
        <h4>Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
        <p class="mb-0">Kelola produk dan pesanan Anda dengan mudah.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Produk</h6>
                        <h2 class="mb-0">{{ $totalProducts }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-box fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Produk Aktif</h6>
                        <h2 class="mb-0">{{ $activeProducts }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Pesanan</h6>
                        <h2 class="mb-0">{{ $totalOrders }}</h2>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-shopping-cart fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Pendapatan</h6>
                        <h4 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-money-bill-wave fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Status -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Stok</h6>
                <h3 class="mb-0">{{ $totalStock }} <small class="text-muted">unit</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-warning mb-2">
                    <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                </h6>
                <h3 class="mb-0">{{ $lowStockProducts }} <small class="text-muted">produk</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <h6 class="text-danger mb-2">
                    <i class="fas fa-times-circle"></i> Stok Habis
                </h6>
                <h3 class="mb-0">{{ $outOfStockProducts }} <small class="text-muted">produk</small></h3>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-shopping-cart"></i> Pesanan Terbaru
        </h5>
        <a href="{{ route('penjual.orders.index') }}" class="btn btn-sm btn-primary">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if($recentOrders->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>Belum ada pesanan</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Produk</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $orderItem)
                            <tr>
                                <td>
                                    <strong>#{{ $orderItem->order->order_number }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($orderItem->product->image)
                                            <img src="{{ $orderItem->product->image_url }}" alt="{{ $orderItem->product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <span>{{ Str::limit($orderItem->product->name, 30) }}</span>
                                    </div>
                                </td>
                                <td>{{ $orderItem->order->user->name }}</td>
                                <td>{{ $orderItem->quantity }}</td>
                                <td>{{ $orderItem->formatted_subtotal }}</td>
                                <td>
                                    <span class="badge bg-{{ $orderItem->order->status_badge }}">
                                        {{ ucfirst($orderItem->order->status) }}
                                    </span>
                                </td>
                                <td>{{ $orderItem->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('penjual.orders.show', $orderItem->order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Admin Dashboard - Cosmetiqu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard Admin</h2>
    <div class="text-muted">
        <i class="fas fa-calendar"></i> {{ now()->format('d F Y') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <h6 class="mb-2">Total Pengguna</h6>
            <h2 class="mb-0">{{ $totalUsers }}</h2>
            <small><i class="fas fa-users"></i> Registered Users</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <h6 class="mb-2">Total Penjual</h6>
            <h2 class="mb-0">{{ $totalPenjual }}</h2>
            <small><i class="fas fa-store"></i> Active Sellers</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card warning">
            <h6 class="mb-2">Total Produk</h6>
            <h2 class="mb-0">{{ $totalProducts }}</h2>
            <small><i class="fas fa-box"></i> Products Listed</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <h6 class="mb-2">Total Kategori</h6>
            <h2 class="mb-0">{{ $totalCategories }}</h2>
            <small><i class="fas fa-tags"></i> Categories</small>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Pesanan</h6>
                <h2 class="mb-0">{{ $totalOrders }}</h2>
                <small class="text-muted">
                    <span class="badge bg-warning">{{ $pendingOrders }} Pending</span>
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Revenue</h6>
                <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                <small class="text-success">
                    <i class="fas fa-arrow-up"></i> Paid Orders
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pending Orders</h6>
                <h2 class="mb-0">{{ $pendingOrders }}</h2>
                <small class="text-warning">
                    <i class="fas fa-clock"></i> Needs Action
                </small>
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
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
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
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->order_number }}</strong>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->orderItems->count() }} items</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_badge }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status_badge }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
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

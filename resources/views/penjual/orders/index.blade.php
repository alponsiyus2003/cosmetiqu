@extends('layouts.penjual')

@section('title', 'Pesanan - Penjual Cosmetiqu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-shopping-cart"></i> Pesanan Produk Saya
    </h2>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('penjual.orders.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status Pesanan</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="mb-2">Pending</h6>
                <h3 class="mb-0">{{ $orderItems->where('order.status', 'pending')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="mb-2">Processing</h6>
                <h3 class="mb-0">{{ $orderItems->where('order.status', 'processing')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="mb-2">Shipped</h6>
                <h3 class="mb-0">{{ $orderItems->where('order.status', 'shipped')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="mb-2">Delivered</h6>
                <h3 class="mb-0">{{ $orderItems->where('order.status', 'delivered')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderItems as $item)
                        <tr>
                            <td>
                                <strong>#{{ $item->order->order_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product->image)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ Str::limit($item->product->name, 25) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $item->order->user->name }}
                                <br>
                                <small class="text-muted">{{ $item->order->phone }}</small>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->formatted_price }}</td>
                            <td><strong>{{ $item->formatted_subtotal }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $item->order->status_badge }}">
                                    {{ ucfirst($item->order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->order->payment_status_badge }}">
                                    {{ ucfirst($item->order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                {{ $item->created_at->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('penjual.orders.show', $item->order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                Belum ada pesanan untuk produk Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orderItems->hasPages())
        <div class="card-footer bg-white">
            {{ $orderItems->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

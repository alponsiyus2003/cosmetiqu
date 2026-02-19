@extends('layouts.app')
@section('title', 'Riwayat Pesanan - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3"><i class="fas fa-receipt text-primary"></i> Riwayat Pesanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pesanan</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Pesanan</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @forelse($orders as $order)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="mb-1 fw-bold"><i class="fas fa-shopping-bag me-2"></i>Pesanan #{{ $order->order_number }}</h6>
                        <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-{{ $order->status_badge }} me-2">{{ ucfirst($order->status) }}</span>
                        <span class="badge bg-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <strong class="d-block mb-2">Produk:</strong>
                        <ul class="list-unstyled">
                            @foreach($order->orderItems->take(3) as $item)
                                <li class="mb-1"><i class="fas fa-box text-muted me-2"></i>{{ $item->product->name }} (x{{ $item->quantity }})</li>
                            @endforeach
                            @if($order->orderItems->count() > 3)
                                <li class="text-muted"><small>dan {{ $order->orderItems->count() - 3 }} produk lainnya...</small></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 class="text-primary fw-bold mb-3">{{ $order->formatted_total }}</h5>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye me-2"></i>Lihat Detail</a>
                        @if($order->status == 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times me-2"></i>Batalkan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-receipt fa-5x text-muted mb-4" style="opacity: 0.3;"></i>
                <h3 class="fw-bold mb-3">Belum Ada Pesanan</h3>
                <p class="text-muted mb-4">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag me-2"></i>Mulai Belanja</a>
            </div>
        </div>
    @endforelse
    @if($orders->hasPages())
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection

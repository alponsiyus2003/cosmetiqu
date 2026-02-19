@extends('layouts.penjual')

@section('title', 'Detail Pesanan - Penjual Cosmetiqu')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('penjual.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penjual.orders.index') }}">Pesanan</a></li>
            <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
        </ol>
    </nav>
    <h2>
        <i class="fas fa-shopping-bag"></i> Pesanan #{{ $order->order_number }}
    </h2>
</div>

<div class="row">
    <!-- Order Items (Your Products Only) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-box me-2"></i>Produk yang Dipesan</h5>
    </div>
    <div class="card-body p-0">
        @foreach($order->orderItems as $item)
            <div class="border-bottom p-3">
                <div class="d-flex align-items-center mb-2">
                    @if($item->product->image)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                    @endif
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-1 fw-semibold">{{ $item->product->name }}</h6>
                        <small class="text-muted"><i class="fas fa-store"></i> {{ $item->seller->name }}</small>
                    </div>
                    <div class="text-end">
                        <p class="mb-0 text-muted">{{ $item->formatted_price }} x {{ $item->quantity }}</p>
                        <strong class="text-primary">{{ $item->formatted_subtotal }}</strong>
                    </div>
                </div>

                @if($order->status == 'delivered')
                    @php
                        $review = \App\Models\Review::where('user_id', auth()->id())
                                                    ->where('product_id', $item->product_id)
                                                    ->where('order_id', $order->id)
                                                    ->first();
                    @endphp

                    @if($review)
                        <div class="mt-2 p-2 bg-light rounded">
                            <small class="text-muted fw-semibold">Review Anda:</small>
                            <div>{!! $review->stars_html !!}</div>
                            @if($review->comment)
                                <p class="mb-2 small">{{ Str::limit($review->comment, 100) }}</p>
                            @endif
                            <div class="d-flex gap-2">
                                <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="mt-2">
                            <a href="{{ route('reviews.create', [$order->id, $item->product_id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-star me-1"></i>Beri Review Produk Ini
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>

    <!-- Order Summary -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-receipt"></i> Ringkasan Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Order Number</small>
                    <h6>#{{ $order->order_number }}</h6>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Tanggal Pesanan</small>
                    <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Metode Pembayaran</small>
                    <p class="mb-0">{{ $order->payment_method }}</p>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted">Status Pesanan</small>
                    <div>
                        <span class="badge bg-{{ $order->status_badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Status Pembayaran</small>
                    <div>
                        <span class="badge bg-{{ $order->payment_status_badge }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted">Produk Anda</small>
                    <h5 class="mb-0">{{ $order->orderItems->count() }} items</h5>
                </div>

                <div>
                    <small class="text-muted">Pendapatan Anda</small>
                    <h4 class="text-success mb-0">Rp {{ number_format($totalPenjual, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle"></i> Status Informasi
                </h6>
            </div>
            <div class="card-body">
                @if($order->status == 'pending')
                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="fas fa-clock"></i>
                            Pesanan menunggu konfirmasi pembayaran. Admin akan memproses pesanan setelah pembayaran dikonfirmasi.
                        </small>
                    </div>
                @elseif($order->status == 'processing')
                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-box"></i>
                            Pesanan sedang diproses. Pastikan produk Anda siap untuk dikirim.
                        </small>
                    </div>
                @elseif($order->status == 'shipped')
                    <div class="alert alert-primary mb-0">
                        <small>
                            <i class="fas fa-truck"></i>
                            Pesanan sedang dalam pengiriman ke customer.
                        </small>
                    </div>
                @elseif($order->status == 'delivered')
                    <div class="alert alert-success mb-0">
                        <small>
                            <i class="fas fa-check-circle"></i>
                            Pesanan telah selesai dan diterima oleh customer. Terima kasih!
                        </small>
                    </div>
                @elseif($order->status == 'cancelled')
                    <div class="alert alert-danger mb-0">
                        <small>
                            <i class="fas fa-times-circle"></i>
                            Pesanan telah dibatalkan.
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

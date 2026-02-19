@extends('layouts.app')
@section('title', 'Keranjang Belanja - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3"><i class="fas fa-shopping-cart text-primary"></i> Keranjang Belanja</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Keranjang</li>
                </ol>
            </nav>
        </div>
    </div>
    @if($carts->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-4" style="opacity: 0.3;"></i>
                <h3 class="fw-bold mb-3">Keranjang Anda Kosong</h3>
                <p class="text-muted mb-4">Belum ada produk di keranjang belanja Anda.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="row fw-semibold">
                            <div class="col-md-5">Produk</div>
                            <div class="col-md-2 text-center">Harga</div>
                            <div class="col-md-2 text-center">Jumlah</div>
                            <div class="col-md-2 text-end">Subtotal</div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @foreach($carts as $cart)
                            <div class="border-bottom p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center">
                                            @if($cart->product->image)
                                                <img src="{{ $cart->product->image_url }}" alt="{{ $cart->product->name }}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-semibold">
                                                    <a href="{{ route('products.show', $cart->product->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $cart->product->name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted"><i class="fas fa-store"></i> {{ $cart->product->seller->name }}</small>
                                                <br>
                                                <small class="text-muted">Stok: {{ $cart->product->stock }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <strong class="text-primary">{{ $cart->product->formatted_price }}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" onclick="this.nextElementSibling.stepDown(); this.form.submit();">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" class="form-control text-center" value="{{ $cart->quantity }}" min="1" max="{{ $cart->product->stock }}" onchange="this.form.submit()">
                                                <button type="button" class="btn btn-outline-secondary" onclick="this.previousElementSibling.stepUp(); this.form.submit();">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <strong class="text-primary fs-5">{{ $cart->formatted_subtotal }}</strong>
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash-alt me-2"></i>Kosongkan Keranjang
                                </button>
                            </form>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i>Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Subtotal ({{ $carts->count() }} item)</span>
                            <strong class="fs-5">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Ongkos Kirim</span>
                            <strong>Rp {{ number_format(20000, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="fw-bold">Total</h5>
                            <h4 class="text-primary fw-bold">Rp {{ number_format($total + 20000, 0, ',', '.') }}</h4>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg mb-3">
                            <i class="fas fa-credit-card me-2"></i>Lanjut ke Checkout
                        </a>
                        <div class="alert alert-info mb-0">
                            <small><i class="fas fa-info-circle me-2"></i>Gratis ongkir untuk pembelian di atas Rp 100.000</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

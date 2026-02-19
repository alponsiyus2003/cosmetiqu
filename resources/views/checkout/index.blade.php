@extends('layouts.app')
@section('title', 'Checkout - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3"><i class="fas fa-credit-card text-primary"></i> Checkout</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-shipping-fast me-2"></i>Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Contoh: 081234567890" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="4" placeholder="Masukkan alamat lengkap termasuk kecamatan, kota, dan kode pos" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Pastikan alamat sudah benar untuk menghindari kesalahan pengiriman</small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Catatan Pesanan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Warna kemasan, pesan khusus untuk penjual, dll.">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-ticket-alt me-2"></i>Kode Voucher</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="input-group input-group-lg">
                            <input type="text" id="voucherCode" name="voucher_code" class="form-control" placeholder="Masukkan kode voucher" value="{{ old('voucher_code') }}">
                            <button type="button" id="applyVoucher" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Terapkan
                            </button>
                        </div>
                        <div id="voucherMessage" class="mt-2"></div>
                        <input type="hidden" id="voucherDiscount" name="voucher_discount" value="0">
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-money-bill me-2"></i>Metode Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check border rounded p-3 mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="Transfer Bank" checked>
                            <label class="form-check-label w-100" for="transfer">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-university fa-2x text-primary me-3"></i>
                                    <div>
                                        <strong class="d-block">Transfer Bank</strong>
                                        <small class="text-muted">Bayar melalui transfer ke rekening bank kami</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="form-check border rounded p-3 mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD (Cash on Delivery)">
                            <label class="form-check-label w-100" for="cod">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hand-holding-usd fa-2x text-success me-3"></i>
                                    <div>
                                        <strong class="d-block">COD (Cash on Delivery)</strong>
                                        <small class="text-muted">Bayar saat barang sampai di tempat Anda</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="form-check border rounded p-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="ewallet" value="E-Wallet">
                            <label class="form-check-label w-100" for="ewallet">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-mobile-alt fa-2x text-info me-3"></i>
                                    <div>
                                        <strong class="d-block">E-Wallet</strong>
                                        <small class="text-muted">OVO, GoPay, Dana, atau LinkAja</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-box me-2"></i>Produk yang Dipesan ({{ $carts->count() }} item)</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($carts as $cart)
                            <div class="border-bottom p-3">
                                <div class="d-flex align-items-center">
                                    @if($cart->product->image)
                                        <img src="{{ $cart->product->image_url }}" alt="{{ $cart->product->name }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $cart->product->name }}</h6>
                                        <small class="text-muted"><i class="fas fa-store"></i> {{ $cart->product->seller->name }}</small>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0 text-muted">{{ $cart->product->formatted_price }} x {{ $cart->quantity }}</p>
                                        <strong class="text-primary">{{ $cart->formatted_subtotal }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <strong id="subtotalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ongkos Kirim</span>
                            <strong id="shippingDisplay">Rp {{ number_format($shippingCost, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom" id="discountRow" style="display: none !important;">
                            <span class="text-success">Diskon Voucher</span>
                            <strong class="text-success" id="discountDisplay">- Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="fw-bold">Total Pembayaran</h5>
                            <h4 class="text-primary fw-bold" id="totalDisplay">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                            <i class="fas fa-check-circle me-2"></i>Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                        </a>
                        <div class="alert alert-info mt-3 mb-0">
                            <small><i class="fas fa-shield-alt me-2"></i>Transaksi Anda aman dan terlindungi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('applyVoucher').addEventListener('click', function() {
    const voucherCode = document.getElementById('voucherCode').value.trim();
    const messageDiv = document.getElementById('voucherMessage');
    const subtotal = {{ $subtotal }};

    if (!voucherCode) {
        messageDiv.innerHTML = '<div class="alert alert-warning mb-0"><small>Masukkan kode voucher terlebih dahulu!</small></div>';
        return;
    }

    // Show loading
    messageDiv.innerHTML = '<div class="alert alert-info mb-0"><small><i class="fas fa-spinner fa-spin me-2"></i>Memeriksa voucher...</small></div>';
    this.disabled = true;

    fetch('{{ route("voucher.check") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            code: voucherCode,
            subtotal: subtotal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const discount = data.data.discount;
            const shippingCost = {{ $shippingCost }};
            const newTotal = subtotal + shippingCost - discount;

            // Update display
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountDisplay').textContent = '- Rp ' + new Intl.NumberFormat('id-ID').format(discount);
            document.getElementById('totalDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(newTotal);
            document.getElementById('voucherDiscount').value = discount;

            messageDiv.innerHTML = '<div class="alert alert-success mb-0"><small><i class="fas fa-check-circle me-2"></i>' + data.message + '</small></div>';
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger mb-0"><small><i class="fas fa-exclamation-circle me-2"></i>' + data.message + '</small></div>';

            // Reset discount
            document.getElementById('discountRow').style.display = 'none';
            document.getElementById('voucherDiscount').value = 0;
            const newTotal = subtotal + {{ $shippingCost }};
            document.getElementById('totalDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(newTotal);
        }
    })
    .catch(error => {
        messageDiv.innerHTML = '<div class="alert alert-danger mb-0"><small>Terjadi kesalahan. Silakan coba lagi.</small></div>';
    })
    .finally(() => {
        this.disabled = false;
    });
});
</script>
@endpush
@endsection

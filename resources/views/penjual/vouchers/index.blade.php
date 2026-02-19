@extends('layouts.penjual')
@section('title', 'Vouchers Aktif - Penjual Cosmetiqu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-ticket-alt"></i> Vouchers Aktif</h2>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Info:</strong> Berikut adalah daftar voucher yang sedang aktif di Cosmetiqu. Customer dapat menggunakan voucher ini untuk mendapatkan diskon pada pembelian produk Anda.
</div>

<div class="row">
    @forelse($vouchers as $voucher)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-{{ $voucher->status_badge }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><code class="text-white">{{ $voucher->code }}</code></h5>
                        <span class="badge bg-light text-dark">{{ $voucher->status_text }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-2">{{ $voucher->name }}</h6>
                    @if($voucher->description)
                        <p class="text-muted small mb-3">{{ $voucher->description }}</p>
                    @endif

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Tipe</small>
                            <span class="badge bg-{{ $voucher->type == 'percentage' ? 'info' : 'success' }}">
                                {{ $voucher->type == 'percentage' ? 'Persentase' : 'Fixed' }}
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Nilai Diskon</small>
                            <p class="mb-0 fw-bold text-primary">{{ $voucher->formatted_value }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Min. Pembelian</small>
                            <p class="mb-0 small">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Penggunaan</small>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ $voucher->usages_count }}</span>
                                @if($voucher->usage_limit)
                                    / {{ $voucher->usage_limit }}
                                @else
                                    / ∞
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted d-block">Periode Aktif</small>
                        <p class="mb-0 small fw-semibold">
                            {{ $voucher->start_date->format('d M Y') }} - {{ $voucher->end_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <a href="{{ route('penjual.vouchers.show', $voucher->id) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-eye me-2"></i>Lihat Detail & Penggunaan
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-ticket-alt fa-5x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h4 class="fw-bold">Belum Ada Voucher Aktif</h4>
                    <p class="text-muted mb-4">Saat ini tidak ada voucher yang sedang aktif di sistem.</p>
                    <a href="{{ route('penjual.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($vouchers->hasPages())
    <div class="mt-4">{{ $vouchers->links() }}</div>
@endif
@endsection

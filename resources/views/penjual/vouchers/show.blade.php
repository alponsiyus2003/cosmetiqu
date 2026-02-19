@extends('layouts.penjual')
@section('title', 'Detail Voucher - Penjual Cosmetiqu')
@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('penjual.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penjual.vouchers.index') }}">Vouchers</a></li>
            <li class="breadcrumb-item active">{{ $voucher->code }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-ticket-alt"></i> Detail Voucher: <code>{{ $voucher->code }}</code></h2>
        <a href="{{ route('penjual.vouchers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Voucher</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Nama Voucher</div>
                    <div class="col-md-8">{{ $voucher->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Kode</div>
                    <div class="col-md-8"><code class="fs-5 bg-light p-2 rounded">{{ $voucher->code }}</code></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Deskripsi</div>
                    <div class="col-md-8">{{ $voucher->description ?: '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Tipe Diskon</div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $voucher->type == 'percentage' ? 'info' : 'success' }}">
                            {{ $voucher->type == 'percentage' ? 'Persentase' : 'Fixed Amount' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Nilai Diskon</div>
                    <div class="col-md-8"><strong class="text-primary fs-4">{{ $voucher->formatted_value }}</strong></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Minimal Pembelian</div>
                    <div class="col-md-8">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</div>
                </div>
                @if($voucher->max_discount)
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Maksimal Diskon</div>
                    <div class="col-md-8">Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Periode Aktif</div>
                    <div class="col-md-8">
                        {{ $voucher->start_date->format('d M Y, H:i') }}
                        <br>
                        s/d {{ $voucher->end_date->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Batas Penggunaan</div>
                    <div class="col-md-8">
                        Total: {{ $voucher->usage_limit ?: 'Unlimited' }}<br>
                        Per User: {{ $voucher->usage_per_user }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 fw-semibold">Status</div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $voucher->status_badge }}">{{ $voucher->status_text }}</span>
                        @if($voucher->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-history me-2"></i>Penggunaan Untuk Produk Saya
                    <span class="badge bg-primary">{{ $myUsages->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th class="text-end">Diskon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myUsages as $usage)
                                <tr>
                                    <td>{{ $usage->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <i class="fas fa-user-circle text-primary me-1"></i>
                                        {{ $usage->user->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('penjual.orders.show', $usage->order_id) }}" class="text-decoration-none">
                                            <i class="fas fa-receipt me-1"></i>{{ $usage->order->order_number }}
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">Rp {{ number_format($usage->discount_amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                        Belum ada customer yang menggunakan voucher ini untuk produk Anda
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3 sticky-top" style="top: 20px;">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2"></i>Statistik Penggunaan</h6>
            </div>
            <div class="card-body">
                <div class="mb-4 pb-3 border-bottom">
                    <small class="text-muted d-block mb-1">Total Penggunaan (Semua Penjual)</small>
                    <h3 class="mb-0 fw-bold text-primary">{{ $voucher->usages->count() }}</h3>
                    @if($voucher->usage_limit)
                        <small class="text-muted">dari {{ $voucher->usage_limit }} limit</small>
                    @else
                        <small class="text-muted">tanpa batas</small>
                    @endif
                </div>

                <div class="mb-4 pb-3 border-bottom">
                    <small class="text-muted d-block mb-1">Digunakan Untuk Produk Saya</small>
                    <h4 class="mb-0 fw-bold text-success">{{ $myUsages->count() }}</h4>
                    <small class="text-muted">customer menggunakan voucher ini</small>
                </div>

                <div class="mb-0">
                    <small class="text-muted d-block mb-1">Total Diskon yang Diberikan</small>
                    <h4 class="mb-0 fw-bold text-danger">
                        Rp {{ number_format($myUsages->sum('discount_amount'), 0, ',', '.') }}
                    </h4>
                    <small class="text-muted">untuk produk Anda</small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>Info</h6>
                <p class="small text-muted mb-0">
                    Voucher ini dibuat oleh Admin dan dapat digunakan oleh semua customer untuk semua produk di Cosmetiqu.
                    Anda dapat melihat statistik penggunaan voucher untuk produk Anda di halaman ini.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

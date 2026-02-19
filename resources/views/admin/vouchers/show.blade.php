@extends('layouts.admin')
@section('title', 'Detail Voucher - Admin Cosmetiqu')
@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
            <li class="breadcrumb-item active">{{ $voucher->code }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-ticket-alt"></i> Detail Voucher: {{ $voucher->code }}</h2>
        <div>
            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
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
                    <div class="col-md-8"><code class="fs-5">{{ $voucher->code }}</code></div>
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
                    <div class="col-md-8"><strong class="text-primary fs-5">{{ $voucher->formatted_value }}</strong></div>
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
                        {{ $voucher->start_date->format('d M Y H:i') }} - {{ $voucher->end_date->format('d M Y H:i') }}
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
                <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Riwayat Penggunaan ({{ $voucher->usages->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Order</th>
                                <th>Diskon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($voucher->usages as $usage)
                                <tr>
                                    <td>{{ $usage->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $usage->user_id) }}">
                                            {{ $usage->user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $usage->order_id) }}">
                                            {{ $usage->order->order_number }}
                                        </a>
                                    </td>
                                    <td><strong class="text-success">Rp {{ number_format($usage->discount_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Voucher belum pernah digunakan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Total Penggunaan</small>
                    <h3 class="mb-0 fw-bold text-primary">{{ $voucher->usages->count() }}</h3>
                    @if($voucher->usage_limit)
                        <small class="text-muted">dari {{ $voucher->usage_limit }} limit</small>
                    @endif
                </div>
                <div class="mb-3">
                    <small class="text-muted">Total Diskon Diberikan</small>
                    <h4 class="mb-0 fw-bold text-success">
                        Rp {{ number_format($voucher->usages->sum('discount_amount'), 0, ',', '.') }}
                    </h4>
                </div>
                <div>
                    <small class="text-muted">Unique Users</small>
                    <h4 class="mb-0 fw-bold">{{ $voucher->usages->unique('user_id')->count() }}</h4>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-{{ $voucher->is_active ? 'warning' : 'success' }} w-100">
                        <i class="fas fa-{{ $voucher->is_active ? 'ban' : 'check' }} me-2"></i>
                        {{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>

                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" {{ $voucher->usages->count() > 0 ? 'disabled' : '' }}>
                        <i class="fas fa-trash me-2"></i>Hapus Voucher
                    </button>
                </form>

                @if($voucher->usages->count() > 0)
                    <small class="text-muted d-block mt-2">*Tidak dapat dihapus karena sudah pernah digunakan</small>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

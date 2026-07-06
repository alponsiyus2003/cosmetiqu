@extends('layouts.admin')

@section('title', 'Laporan Pendapatan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Laporan Pendapatan</h2>
        <p class="text-muted mb-0">Export hasil penjualan menjadi CSV, Excel, atau PDF</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $request->start_date ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ $request->end_date ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="pending" {{ ($request->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ ($request->status ?? '') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ ($request->status ?? '') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ ($request->status ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ ($request->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Pendapatan</h6>
                <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted">Jumlah Order</h6>
                <h3 class="mb-0">{{ $totalOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="text-muted">Export</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.revenue-report.export', ['type' => 'csv', ...request()->query()]) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-file-csv me-1"></i>CSV</a>
                    <a href="{{ route('admin.revenue-report.export', ['type' => 'excel', ...request()->query()]) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
                    <a href="{{ route('admin.revenue-report.export', ['type' => 'pdf', ...request()->query()]) }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($orders->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>Tidak ada data pendapatan untuk periode ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Pembeli</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                                <td><span class="badge bg-{{ $order->payment_status_badge }}">{{ $order->payment_status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.penjual')

@section('title', 'Laporan Pendapatan - Penjual')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Laporan Pendapatan</h2>
        <p class="text-muted mb-0">Export hasil penjualan produk Anda menjadi CSV, Excel, atau PDF</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $request->start_date ?? '' }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ $request->end_date ?? '' }}">
            </div>
            <div class="col-md-2">
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
                <h6 class="text-muted">Jumlah Transaksi</h6>
                <h3 class="mb-0">{{ $totalOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="text-muted">Export</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('penjual.revenue-report.export', ['type' => 'csv', ...request()->query()]) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-file-csv me-1"></i>CSV</a>
                    <a href="{{ route('penjual.revenue-report.export', ['type' => 'excel', ...request()->query()]) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
                    <a href="{{ route('penjual.revenue-report.export', ['type' => 'pdf', ...request()->query()]) }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($items->isEmpty())
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
                            <th>Produk</th>
                            <th>Pembeli</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>#{{ $item->order->order_number }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->order->user->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->formatted_subtotal }}</td>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

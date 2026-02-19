@extends('layouts.admin')
@section('title', 'Kelola Voucher - Admin Cosmetiqu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-ticket-alt"></i> Kelola Voucher</h2>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Voucher
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Min. Pembelian</th>
                        <th>Periode</th>
                        <th>Penggunaan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td><code class="fs-6">{{ $voucher->code }}</code></td>
                            <td>{{ $voucher->name }}</td>
                            <td>
                                <span class="badge bg-{{ $voucher->type == 'percentage' ? 'info' : 'success' }}">
                                    {{ $voucher->type == 'percentage' ? 'Persentase' : 'Fixed' }}
                                </span>
                            </td>
                            <td><strong>{{ $voucher->formatted_value }}</strong></td>
                            <td>Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                            <td>
                                <small>{{ $voucher->start_date->format('d M Y') }}</small><br>
                                <small>{{ $voucher->end_date->format('d M Y') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $voucher->usages_count }}
                                    @if($voucher->usage_limit)
                                        / {{ $voucher->usage_limit }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $voucher->status_badge }}">
                                    {{ $voucher->status_text }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $voucher->is_active ? 'secondary' : 'success' }}" title="{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $voucher->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus voucher ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada voucher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($vouchers->hasPages())
        <div class="card-footer bg-white">
            {{ $vouchers->links() }}
        </div>
    @endif
</div>
@endsection

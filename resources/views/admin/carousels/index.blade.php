@extends('layouts.admin')
@section('title', 'Kelola Carousel - Admin Cosmetiqu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-images"></i> Kelola Carousel</h2>
    <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Carousel
    </a>
</div>

<div class="row">
    @forelse($carousels as $carousel)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $carousel->image_url }}" class="card-img-top" alt="{{ $carousel->title }}" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $carousel->title }}</h5>
                        <span class="badge bg-{{ $carousel->is_active ? 'success' : 'secondary' }}">
                            {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-muted small mb-2">Order: {{ $carousel->order }}</p>
                    @if($carousel->description)
                        <p class="card-text text-muted">{{ Str::limit($carousel->description, 100) }}</p>
                    @endif
                    @if($carousel->button_text)
                        <p class="mb-0">
                            <span class="badge bg-info">{{ $carousel->button_text }}</span>
                            @if($carousel->button_link)
                                <small class="text-muted">→ {{ Str::limit($carousel->button_link, 30) }}</small>
                            @endif
                        </p>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.carousels.edit', $carousel->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.carousels.destroy', $carousel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus carousel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-images fa-5x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h4>Belum Ada Carousel</h4>
                    <p class="text-muted mb-4">Tambahkan carousel untuk ditampilkan di halaman utama.</p>
                    <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Carousel
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection

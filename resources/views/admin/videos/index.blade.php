@extends('layouts.admin')
@section('title', 'Kelola Video - Admin Cosmetiqu')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-video text-primary me-2"></i>Kelola Video
            </h2>
            <p class="text-muted mb-0">Manajemen video produk dari semua seller</p>
        </div>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Upload Video
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-video fa-lg text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Video</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Video Aktif</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['active'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-eye-slash fa-lg text-warning"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Nonaktif</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['inactive'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-eye fa-lg text-info"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Views</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['total_views']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Videos Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($videos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Video</th>
                                <th>Produk</th>
                                <th>Seller</th>
                                <th class="text-center">Views</th>
                                <th class="text-center">Likes</th>
                                <th class="text-center">Komentar</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($videos as $video)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative flex-shrink-0">
                                                <video src="{{ $video->video_url }}"
                                                       class="rounded"
                                                       style="width: 80px; height: 80px; object-fit: cover;"></video>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="fas fa-play-circle fa-2x text-white opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-semibold">
                                                    {{ $video->title ?? 'Video ' . $video->id }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $video->created_at->format('d M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $video->product->slug) }}"
                                           class="text-decoration-none fw-semibold"
                                           target="_blank">
                                            {{ Str::limit($video->product->name, 30) }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $video->user->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $video->formatted_views }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $video->formatted_likes }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $video->comments_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($video->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('videos.show', $video->id) }}"
                                               class="btn btn-info"
                                               target="_blank"
                                               title="Lihat Video">
                                                <i class="fas fa-play"></i>
                                            </a>
                                            <a href="{{ route('admin.videos.show', $video->id) }}"
                                               class="btn btn-primary"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.videos.toggle', $video->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-{{ $video->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $video->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $video->is_active ? 'eye-slash' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.videos.destroy', $video->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus video ini?')"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($videos->hasPages())
                    <div class="px-4 py-3">
                        {{ $videos->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-video fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h5 class="text-muted">Belum Ada Video</h5>
                    <p class="text-muted mb-4">Upload video pertama untuk promosi produk.</p>
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Upload Video
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

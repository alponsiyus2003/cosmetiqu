@extends('layouts.penjual')
@section('title', 'Video Saya - Penjual Cosmetiqu')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-video text-primary me-2"></i>Video Saya
            </h2>
            <p class="text-muted mb-0">Kelola video produk Anda</p>
        </div>
        <a href="{{ route('penjual.videos.create') }}" class="btn btn-primary">
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
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="fas fa-heart fa-lg text-danger"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Likes</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['total_likes']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Videos Grid --}}
    @if($videos->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($videos as $video)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        {{-- Video Thumbnail --}}
                        <div class="position-relative" style="height: 250px; background: #000;">
                            <video src="{{ $video->video_url }}"
                                   class="w-100 h-100"
                                   style="object-fit: cover;"></video>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                 style="background: rgba(0,0,0,0.3);">
                                <i class="fas fa-play-circle fa-4x text-white opacity-75"></i>
                            </div>

                            {{-- Stats Overlay --}}
                            <div class="position-absolute bottom-0 start-0 w-100 p-2"
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                <div class="d-flex gap-3 text-white small">
                                    <span><i class="fas fa-eye me-1"></i>{{ $video->formatted_views }}</span>
                                    <span><i class="fas fa-heart me-1"></i>{{ $video->formatted_likes }}</span>
                                    <span><i class="fas fa-comment me-1"></i>{{ $video->comments_count }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Video Info --}}
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">
                                {{ $video->title ?? 'Video ' . $video->id }}
                            </h6>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-box me-1"></i>{{ $video->product->name }}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-calendar me-1"></i>{{ $video->created_at->format('d M Y') }}
                            </p>

                            {{-- Actions --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('videos.show', $video->id) }}"
                                   class="btn btn-sm btn-info flex-fill"
                                   target="_blank">
                                    <i class="fas fa-play me-1"></i>Tonton
                                </a>
                                <a href="{{ route('penjual.videos.show', $video->id) }}"
                                   class="btn btn-sm btn-primary flex-fill">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                <form action="{{ route('penjual.videos.destroy', $video->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus video ini?')"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($videos->hasPages())
            <div class="d-flex justify-content-center">
                {{ $videos->links() }}
            </div>
        @endif
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-video fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted">Belum Ada Video</h5>
                <p class="text-muted mb-4">Upload video pertama Anda untuk mempromosikan produk.</p>
                <a href="{{ route('penjual.videos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Upload Video
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

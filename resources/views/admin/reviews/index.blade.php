@extends('layouts.admin')
@section('title', 'Kelola Review - Admin Cosmetiqu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-star"></i> Kelola Review</h2>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <h6><i class="fas fa-star me-2"></i>Total Review</h6>
            <h2>{{ $stats['total'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <h6><i class="fas fa-check-circle me-2"></i>Disetujui</h6>
            <h2>{{ $stats['approved'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <h6><i class="fas fa-clock me-2"></i>Pending</h6>
            <h2>{{ $stats['pending'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <h6><i class="fas fa-photo-video me-2"></i>Dengan Media</h6>
            <h2>{{ $stats['with_media'] }}</h2>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reviews.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="rating" class="form-select">
                        <option value="">Semua Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk atau user..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @forelse($reviews as $review)
            <div class="p-4 border-bottom">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start mb-3">
                            @if($review->user->avatar)
                                <img src="{{ $review->user->avatar_url }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $review->user->name }}</h6>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    {!! $review->stars_html !!}
                                    <span class="badge bg-warning text-dark">{{ $review->rating_label }}</span>
                                    @if($review->is_verified_purchase)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $review->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>

                        <div class="mb-2">
                            <strong>Produk:</strong>
                            <a href="{{ route('admin.products.show', $review->product_id) }}">{{ $review->product->name }}</a>
                        </div>

                        @if($review->comment)
                            <p class="text-muted mb-3">{{ $review->comment }}</p>
                        @endif

                        @if($review->media->count() > 0)
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                @foreach($review->media as $media)
                                    @if($media->is_image)
                                        <a href="{{ $media->url }}" target="_blank">
                                            <img src="{{ $media->url }}" class="rounded shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                        </a>
                                    @else
                                        <a href="{{ $media->url }}" target="_blank" class="position-relative d-inline-block">
                                            <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                                <i class="fas fa-play-circle fa-2x text-white"></i>
                                            </div>
                                            <span class="position-absolute bottom-0 start-0 badge bg-dark m-1" style="font-size: 9px;">VIDEO</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if($review->replies->count() > 0)
                            <div class="alert alert-light border p-2 mb-0">
                                <small class="text-muted"><i class="fas fa-reply me-1"></i>{{ $review->replies->count() }} balasan</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4 text-end">
                        <span class="badge bg-{{ $review->is_approved ? 'success' : 'warning' }} mb-2 d-block">
                            {{ $review->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                        <div class="btn-group-vertical w-100">
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary mb-1">
                                <i class="fas fa-eye me-1"></i>Detail & Balas
                            </a>
                            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="mb-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-{{ $review->is_approved ? 'warning' : 'success' }} w-100">
                                    <i class="fas fa-{{ $review->is_approved ? 'ban' : 'check' }} me-1"></i>
                                    {{ $review->is_approved ? 'Sembunyikan' : 'Approve' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-star fa-5x text-muted mb-3" style="opacity: 0.3;"></i>
                <h4>Belum Ada Review</h4>
                <p class="text-muted">Belum ada review yang masuk.</p>
            </div>
        @endforelse
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection

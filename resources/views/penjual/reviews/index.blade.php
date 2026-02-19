@extends('layouts.penjual')
@section('title', 'Review Produk Saya - Penjual Cosmetiqu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-star"></i> Review Produk Saya</h2>
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
            <h6><i class="fas fa-reply me-2"></i>Sudah Dibalas</h6>
            <h2>{{ $stats['replied'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <h6><i class="fas fa-clock me-2"></i>Belum Dibalas</h6>
            <h2>{{ $stats['not_replied'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <h6><i class="fas fa-star-half-alt me-2"></i>Rata-rata Rating</h6>
            <h2>{{ number_format($stats['avg_rating'], 1) }}</h2>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('penjual.reviews.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="rating" class="form-select">
                        <option value="">Semua Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="replied" class="form-select">
                        <option value="">Semua Status Balasan</option>
                        <option value="yes" {{ request('replied') == 'yes' ? 'selected' : '' }}>Sudah Dibalas</option>
                        <option value="no" {{ request('replied') == 'no' ? 'selected' : '' }}>Belum Dibalas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
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
                            <div>
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
                            <strong>Produk:</strong> {{ $review->product->name }}
                        </div>

                        @if($review->comment)
                            <p class="text-muted mb-3">{{ Str::limit($review->comment, 150) }}</p>
                        @endif

                        @if($review->media->count() > 0)
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                @foreach($review->media->take(4) as $media)
                                    @if($media->is_image)
                                        <img src="{{ $media->url }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-dark rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                            <i class="fas fa-play-circle text-white fa-2x"></i>
                                        </div>
                                    @endif
                                @endforeach
                                @if($review->media->count() > 4)
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white fw-bold" style="width: 60px; height: 60px;">
                                        +{{ $review->media->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($review->replies->count() > 0)
                            <div class="alert alert-light border p-2 mb-0">
                                <small><i class="fas fa-check-circle text-success me-1"></i>Sudah dibalas {{ $review->replies->count() }}x</small>
                            </div>
                        @else
                            <div class="alert alert-warning p-2 mb-0">
                                <small><i class="fas fa-exclamation-circle me-1"></i>Belum dibalas</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4 text-end">
                        <a href="{{ route('penjual.reviews.show', $review->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-reply me-2"></i>Lihat & Balas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-star fa-5x text-muted mb-3" style="opacity: 0.3;"></i>
                <h4>Belum Ada Review</h4>
                <p class="text-muted">Produk Anda belum mendapatkan review.</p>
            </div>
        @endforelse
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection

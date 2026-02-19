@extends('layouts.admin')
@section('title', 'Detail Review - Admin Cosmetiqu')
@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Review</a></li>
            <li class="breadcrumb-item active">Detail Review</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-star"></i> Detail Review</h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-4">
                    @if($review->user->avatar)
                        <img src="{{ $review->user->avatar_url }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold fs-4" style="width: 60px; height: 60px;">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="fw-bold mb-1">{{ $review->user->name }}</h5>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            {!! $review->stars_html !!}
                            <span class="badge bg-warning text-dark fs-6">{{ $review->rating_label }}</span>
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d M Y, H:i') }}</small>
                        @if($review->is_verified_purchase)
                            <span class="badge bg-success ms-2"><i class="fas fa-check-circle me-1"></i>Verified Purchase</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4 p-3 bg-light rounded">
                    <strong>Produk:</strong>
                    <a href="{{ route('admin.products.show', $review->product_id) }}" class="ms-2">{{ $review->product->name }}</a>
                </div>

                @if($review->comment)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Ulasan:</h6>
                        <p class="mb-0 p-3 bg-light rounded">{{ $review->comment }}</p>
                    </div>
                @endif

                @if($review->media->count() > 0)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Media ({{ $review->media->count() }} file):</h6>
                        <div class="row g-3">
                            @foreach($review->media as $media)
                                <div class="col-4 col-md-3">
                                    @if($media->is_image)
                                        <a href="{{ $media->url }}" target="_blank" class="d-block">
                                            <img src="{{ $media->url }}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;">
                                        </a>
                                    @else
                                        <div class="position-relative">
                                            <video src="{{ $media->url }}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;" controls></video>
                                            <span class="position-absolute top-0 start-0 badge bg-dark m-1">VIDEO</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <hr>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-comments me-2"></i>Balasan ({{ $review->replies->count() }})</h6>

                    @forelse($review->replies as $reply)
                        <div class="d-flex mb-3 p-3 bg-light rounded">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" style="width: 40px; height: 40px; background: linear-gradient(135deg, #6030C1, #8B5CF6);">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $reply->user->name }}</strong>
                                        <span class="badge bg-{{ $reply->role_badge }} ms-2">{{ $reply->role_label }}</span>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                        <form action="{{ route('admin.reviews.reply.delete', $reply->id) }}" method="POST" onsubmit="return confirm('Hapus balasan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <p class="mb-0 mt-1">{{ $reply->reply }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada balasan.</p>
                    @endforelse
                </div>

                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-reply me-2"></i>Balas Review (sebagai Admin)</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="reply" class="form-control @error('reply') is-invalid @enderror" rows="4" placeholder="Tulis balasan Anda...">{{ old('reply') }}</textarea>
                                @error('reply')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Balasan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Info Review</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Status:</strong>
                    <span class="badge bg-{{ $review->is_approved ? 'success' : 'warning' }} ms-1">
                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </p>
                <p class="mb-2"><strong>Rating:</strong> {{ $review->rating }}/5</p>
                <p class="mb-2"><strong>Media:</strong> {{ $review->media->count() }} file</p>
                <p class="mb-2"><strong>Balasan:</strong> {{ $review->replies->count() }}</p>
                <p class="mb-0"><strong>Tanggal:</strong> {{ $review->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-{{ $review->is_approved ? 'warning' : 'success' }} w-100">
                        <i class="fas fa-{{ $review->is_approved ? 'ban' : 'check' }} me-2"></i>
                        {{ $review->is_approved ? 'Sembunyikan Review' : 'Approve Review' }}
                    </button>
                </form>
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash me-2"></i>Hapus Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

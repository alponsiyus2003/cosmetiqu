@extends('layouts.penjual')
@section('title', 'Detail Review - Penjual Cosmetiqu')
@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('penjual.reviews.index') }}">Review</a></li>
            <li class="breadcrumb-item active">Detail Review</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-star"></i> Detail Review & Balas</h2>
        <a href="{{ route('penjual.reviews.index') }}" class="btn btn-secondary">
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
                            @if($review->is_verified_purchase)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified Purchase</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-light rounded">
                    <strong>Produk:</strong> {{ $review->product->name }}
                </div>

                @if($review->comment)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Ulasan Pembeli:</h6>
                        <p class="mb-0 p-3 bg-light rounded fs-6">{{ $review->comment }}</p>
                    </div>
                @endif

                @if($review->media->count() > 0)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-photo-video me-2"></i>Media Unboxing ({{ $review->media->count() }} file):
                        </h6>
                        <div class="row g-3">
                            @foreach($review->media as $media)
                                <div class="col-4 col-md-3">
                                    @if($media->is_image)
                                        <a href="{{ $media->url }}" target="_blank" class="d-block">
                                            <img src="{{ $media->url }}" class="img-thumbnail w-100 shadow-sm" style="height: 130px; object-fit: cover;">
                                        </a>
                                    @else
                                        <div class="position-relative">
                                            <video src="{{ $media->url }}" class="img-thumbnail w-100 shadow-sm" style="height: 130px; object-fit: cover;" controls></video>
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
                        <div class="d-flex mb-3 p-3 rounded" style="background: #F5F3FF;">
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
                                        @if($reply->user_id == auth()->id())
                                            <form action="{{ route('penjual.reviews.reply.delete', $reply->id) }}" method="POST" onsubmit="return confirm('Hapus balasan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <p class="mb-0 mt-1">{{ $reply->reply }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted fst-italic">Belum ada balasan. Segera balas untuk meningkatkan kepercayaan pembeli!</p>
                    @endforelse
                </div>

                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-reply me-2"></i>Balas Review ini (sebagai Penjual)</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('penjual.reviews.reply', $review->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="reply" class="form-control @error('reply') is-invalid @enderror" rows="4" placeholder="Tulis balasan Anda kepada pelanggan...">{{ old('reply') }}</textarea>
                                @error('reply')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Berikan balasan yang ramah dan informatif untuk meningkatkan kepercayaan pembeli.</small>
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
        <div class="card shadow-sm mb-3 sticky-top" style="top: 20px;">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-box me-2"></i>Info Produk</h6>
            </div>
            <div class="card-body">
                @if($review->product->image)
                    <img src="{{ $review->product->image_url }}" class="img-fluid rounded mb-3 shadow-sm" alt="{{ $review->product->name }}" style="height: 150px; object-fit: cover; width: 100%;">
                @endif
                <h6 class="fw-bold">{{ $review->product->name }}</h6>
                <p class="text-primary fw-bold mb-0">{{ $review->product->formatted_price }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-lightbulb me-2 text-warning"></i>Tips Membalas Review</h6>
            </div>
            <div class="card-body">
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2">Ucapkan terima kasih kepada pembeli</li>
                    <li class="mb-2">Berikan solusi jika ada keluhan</li>
                    <li class="mb-2">Gunakan bahasa yang sopan dan ramah</li>
                    <li class="mb-2">Jawab semua pertanyaan yang diajukan</li>
                    <li class="mb-0">Hindari perdebatan di kolom review</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

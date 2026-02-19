@extends('layouts.app')
@section('title', 'Beri Review - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-star me-2"></i>Beri Review Produk</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold">{{ $product->name }}</h5>
                            <p class="text-muted mb-0">{{ $product->category->name }}</p>
                            <small class="text-muted"><i class="fas fa-receipt me-1"></i>Order: {{ $order->order_number }}</small>
                            <br>
                            <span class="badge bg-success mt-1"><i class="fas fa-check-circle me-1"></i>Verified Purchase</span>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store', [$order->id, $product->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold fs-5">Rating <span class="text-danger">*</span></label>
                            <div class="rating-wrapper">
                                <div class="rating-stars">
                                    <input type="radio" name="rating" id="star5" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required>
                                    <label for="star5" title="Sangat Bagus"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star4" value="4" {{ old('rating') == 4 ? 'checked' : '' }}>
                                    <label for="star4" title="Bagus"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star3" value="3" {{ old('rating') == 3 ? 'checked' : '' }}>
                                    <label for="star3" title="Cukup"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star2" value="2" {{ old('rating') == 2 ? 'checked' : '' }}>
                                    <label for="star2" title="Buruk"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star1" value="1" {{ old('rating') == 1 ? 'checked' : '' }}>
                                    <label for="star1" title="Sangat Buruk"><i class="fas fa-star"></i></label>
                                </div>
                                <div id="ratingLabel" class="ms-3 fs-5 fw-bold text-warning"></div>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ulasan Anda</label>
                            <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="5" placeholder="Ceritakan pengalaman Anda dengan produk ini...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-camera me-2"></i>Foto & Video (Opsional)
                            </label>

                            <div class="upload-area p-4 text-center rounded border-2 border-dashed mb-3" id="uploadArea" style="border: 2px dashed #6030C1; cursor: pointer; background: #F5F3FF;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h6 class="fw-bold">Klik atau Drag & Drop file disini</h6>
                                <p class="text-muted small mb-2">Dukung: JPG, PNG, GIF, MP4, MOV, AVI</p>
                                <p class="text-muted small mb-0">Maksimal 50MB per file, maksimal 10 file</p>
                                <input type="file" name="media[]" id="mediaInput" class="d-none" accept="image/*,video/*" multiple>
                            </div>

                            <div id="mediaPreview" class="row g-2 mt-2"></div>

                            @error('media.*')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Tips:</strong> Upload foto atau video unboxing produk untuk membantu calon pembeli lain!
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Review
                            </button>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.rating-wrapper { display: flex; align-items: center; }
.rating-stars { display: flex; flex-direction: row-reverse; gap: 5px; }
.rating-stars input[type="radio"] { display: none; }
.rating-stars label { cursor: pointer; font-size: 2.5rem; color: #ddd; transition: all 0.2s; }
.rating-stars label:hover,
.rating-stars label:hover ~ label,
.rating-stars input[type="radio"]:checked ~ label { color: #ffc107; transform: scale(1.1); }
.upload-area:hover { background: #EDE9FE !important; }
.media-preview-item { position: relative; }
.media-preview-item .remove-btn { position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: #dc3545; color: white; border: none; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.media-preview-item img,
.media-preview-item video { width: 100%; height: 100px; object-fit: cover; border-radius: 8px; }
.video-badge { position: absolute; bottom: 5px; left: 5px; background: rgba(0,0,0,0.7); color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px; }
</style>
@endpush

@push('scripts')
<script>
const ratingLabels = { 1: 'Sangat Buruk', 2: 'Buruk', 3: 'Cukup', 4: 'Bagus', 5: 'Sangat Bagus' };
const stars = document.querySelectorAll('.rating-stars input');
stars.forEach(star => {
    star.addEventListener('change', function() {
        document.getElementById('ratingLabel').textContent = ratingLabels[this.value];
    });
});

// Upload area click
document.getElementById('uploadArea').addEventListener('click', function() {
    document.getElementById('mediaInput').click();
});

// Drag and drop
const uploadArea = document.getElementById('uploadArea');
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.style.background = '#EDE9FE';
});
uploadArea.addEventListener('dragleave', () => {
    uploadArea.style.background = '#F5F3FF';
});
uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.style.background = '#F5F3FF';
    handleFiles(e.dataTransfer.files);
});

document.getElementById('mediaInput').addEventListener('change', function() {
    handleFiles(this.files);
});

function handleFiles(files) {
    const preview = document.getElementById('mediaPreview');

    Array.from(files).forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-4 col-md-3 media-preview-item';

        const reader = new FileReader();

        if (file.type.startsWith('image/')) {
            reader.onload = (e) => {
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail">
                        <button class="remove-btn" onclick="this.closest('.col-4, .col-md-3').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            reader.onload = (e) => {
                col.innerHTML = `
                    <div class="position-relative">
                        <video src="${e.target.result}" class="img-thumbnail" muted></video>
                        <span class="video-badge"><i class="fas fa-play me-1"></i>Video</span>
                        <button class="remove-btn" onclick="this.closest('.col-4, .col-md-3').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }

        preview.appendChild(col);
    });
}
</script>
@endpush
@endsection

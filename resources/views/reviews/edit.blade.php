@extends('layouts.app')
@section('title', 'Edit Review - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.show', $review->order_id) }}">{{ $review->order->order_number }}</a></li>
                    <li class="breadcrumb-item active">Edit Review</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Review Produk</h5>
                </div>
                <div class="card-body p-4">

                    {{-- Product Info --}}
                    <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                        @if($review->product->image)
                            <img src="{{ $review->product->image_url }}"
                                 alt="{{ $review->product->name }}"
                                 class="rounded shadow-sm flex-shrink-0"
                                 style="width: 90px; height: 90px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                                 style="width: 90px; height: 90px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold">{{ $review->product->name }}</h5>
                            <p class="text-muted mb-0">{{ $review->product->category->name }}</p>
                            <small class="text-muted">
                                <i class="fas fa-receipt me-1"></i>Order: {{ $review->order->order_number }}
                            </small>
                            <br>
                            <span class="badge bg-success mt-1">
                                <i class="fas fa-check-circle me-1"></i>Verified Purchase
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('reviews.update', $review->id) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Rating --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold fs-5">
                                Rating <span class="text-danger">*</span>
                            </label>
                            <div class="rating-wrapper">
                                <div class="rating-stars">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" id="star{{ $i }}"
                                               value="{{ $i }}"
                                               {{ old('rating', $review->rating) == $i ? 'checked' : '' }}
                                               {{ $i == 5 ? 'required' : '' }}>
                                        <label for="star{{ $i }}" title="{{ ['','Sangat Buruk','Buruk','Cukup','Bagus','Sangat Bagus'][$i] }}">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <div id="ratingLabel" class="ms-3 fs-5 fw-bold text-warning">
                                    {{ $review->rating_label }}
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Comment --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ulasan Anda</label>
                            <textarea name="comment"
                                      class="form-control @error('comment') is-invalid @enderror"
                                      rows="5"
                                      placeholder="Ceritakan pengalaman Anda dengan produk ini...">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>

                        {{-- Existing Media --}}
                        @if($review->media->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-photo-video me-2"></i>
                                    Media Saat Ini ({{ $review->media->count() }} file)
                                </label>
                                <div class="row g-2">
                                    @foreach($review->media as $media)
                                        <div class="col-4 col-md-3" id="media-item-{{ $media->id }}">
                                            <div class="position-relative">
                                                @if($media->is_image)
                                                    {{-- Image Preview --}}
                                                    <div class="position-relative"
                                                         onclick="openMediaModal('{{ $media->url }}', 'image')"
                                                         style="cursor: pointer;">
                                                        <img src="{{ $media->url }}"
                                                             class="img-thumbnail w-100"
                                                             style="height: 110px; object-fit: cover;">
                                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                                             style="background: rgba(0,0,0,0.2); opacity: 0; transition: opacity 0.2s;"
                                                             onmouseover="this.style.opacity='1'"
                                                             onmouseout="this.style.opacity='0'">
                                                            <i class="fas fa-search-plus fa-2x text-white"></i>
                                                        </div>
                                                    </div>
                                                    <span class="position-absolute top-0 start-0 badge bg-info m-1" style="font-size: 9px;">FOTO</span>
                                                @else
                                                    {{-- Video Preview --}}
                                                    <div class="position-relative"
                                                         onclick="openMediaModal('{{ $media->url }}', 'video')"
                                                         style="cursor: pointer;">
                                                        <video src="{{ $media->url }}"
                                                               class="img-thumbnail w-100"
                                                               style="height: 110px; object-fit: cover;"></video>
                                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                                             style="background: rgba(0,0,0,0.5);">
                                                            <i class="fas fa-play-circle fa-3x text-white"></i>
                                                        </div>
                                                    </div>
                                                    <span class="position-absolute top-0 start-0 badge bg-danger m-1" style="font-size: 9px;">VIDEO</span>
                                                @endif

                                                {{-- Delete Checkbox --}}
                                                <div class="form-check position-absolute bottom-0 end-0 m-1"
                                                     style="background: rgba(255,255,255,0.9); padding: 3px 6px; border-radius: 4px;">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="delete_media[]"
                                                           value="{{ $media->id }}"
                                                           id="del_{{ $media->id }}"
                                                           onchange="toggleDeleteMedia({{ $media->id }}, this.checked)">
                                                    <label class="form-check-label text-danger small fw-bold"
                                                           for="del_{{ $media->id }}">Hapus</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Centang "Hapus" pada media yang ingin dihapus. Klik media untuk melihat lebih besar.
                                </small>
                            </div>
                        @endif

                        {{-- Upload New Media --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Foto & Video Baru (Opsional)
                            </label>

                            <div class="upload-area p-4 text-center rounded mb-3"
                                 id="uploadArea"
                                 style="border: 2px dashed #6030C1; cursor: pointer; background: #F5F3FF;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h6 class="fw-bold">Klik atau Drag & Drop file disini</h6>
                                <p class="text-muted small mb-2">Dukung: JPG, PNG, GIF, MP4, MOV, AVI</p>
                                <p class="text-muted small mb-0">Maksimal 50MB per file</p>
                                <input type="file"
                                       name="media[]"
                                       id="mediaInput"
                                       class="d-none"
                                       accept="image/*,video/*"
                                       multiple>
                            </div>

                            <div id="newMediaPreview" class="row g-2"></div>

                            @error('media.*')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Review
                            </button>
                            <a href="{{ route('orders.show', $review->order_id) }}"
                               class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Media Lightbox Modal --}}
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white ms-auto"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3" id="mediaModalBody"></div>
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
#media-item.deleting img,
#media-item.deleting video { opacity: 0.4; filter: grayscale(100%); }
</style>
@endpush

@push('scripts')
<script>
// Rating label
const ratingLabels = {1:'Sangat Buruk',2:'Buruk',3:'Cukup',4:'Bagus',5:'Sangat Bagus'};
document.querySelectorAll('.rating-stars input').forEach(star => {
    star.addEventListener('change', function() {
        document.getElementById('ratingLabel').textContent = ratingLabels[this.value];
    });
});

// Toggle delete overlay
function toggleDeleteMedia(id, isChecked) {
    const item = document.getElementById('media-item-' + id);
    const imgs = item.querySelectorAll('img, video');
    imgs.forEach(el => {
        el.style.opacity = isChecked ? '0.35' : '1';
        el.style.filter = isChecked ? 'grayscale(100%)' : 'none';
    });
}

// Upload area
document.getElementById('uploadArea').addEventListener('click', function() {
    document.getElementById('mediaInput').click();
});

const uploadArea = document.getElementById('uploadArea');
uploadArea.addEventListener('dragover', e => {
    e.preventDefault();
    uploadArea.style.background = '#EDE9FE';
});
uploadArea.addEventListener('dragleave', () => {
    uploadArea.style.background = '#F5F3FF';
});
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.style.background = '#F5F3FF';
    handleFiles(e.dataTransfer.files);
});

document.getElementById('mediaInput').addEventListener('change', function() {
    handleFiles(this.files);
});

function handleFiles(files) {
    const preview = document.getElementById('newMediaPreview');
    Array.from(files).forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-4 col-md-3';
        const reader = new FileReader();
        if (file.type.startsWith('image/')) {
            reader.onload = e => {
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail w-100" style="height:110px;object-fit:cover;">
                        <span class="position-absolute top-0 start-0 badge bg-info m-1" style="font-size:9px;">BARU</span>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0"
                                style="width:22px;height:22px;font-size:11px;line-height:1;"
                                onclick="this.closest('.col-4').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            reader.onload = e => {
                col.innerHTML = `
                    <div class="position-relative">
                        <video src="${e.target.result}" class="img-thumbnail w-100" style="height:110px;object-fit:cover;" muted></video>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background:rgba(0,0,0,0.4);pointer-events:none;">
                            <i class="fas fa-play-circle fa-2x text-white"></i>
                        </div>
                        <span class="position-absolute top-0 start-0 badge bg-danger m-1" style="font-size:9px;">VIDEO BARU</span>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0"
                                style="width:22px;height:22px;font-size:11px;line-height:1;"
                                onclick="this.closest('.col-4').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
            };
            reader.readAsDataURL(file);
        }
        preview.appendChild(col);
    });
}

// Lightbox
function openMediaModal(url, type) {
    const body = document.getElementById('mediaModalBody');
    if (type === 'image') {
        body.innerHTML = `<img src="${url}" class="img-fluid rounded" style="max-height:80vh;">`;
    } else {
        body.innerHTML = `<video src="${url}" class="w-100 rounded" style="max-height:80vh;" controls autoplay></video>`;
    }
    new bootstrap.Modal(document.getElementById('mediaModal')).show();
}
</script>
@endpush
@endsection

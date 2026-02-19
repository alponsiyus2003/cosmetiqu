@extends('layouts.penjual')
@section('title', 'Upload Video Produk - Penjual Cosmetiqu')

@push('styles')
<style>
.upload-container {
    max-width: 800px;
    margin: 0 auto;
}

.upload-zone {
    border: 3px dashed #6030C1;
    border-radius: 16px;
    padding: 60px 40px;
    text-align: center;
    background: #F5F3FF;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-zone:hover {
    background: #EDE9FE;
    border-color: #4C1D95;
}

.upload-zone.dragover {
    background: #DDD6FE;
    border-color: #4C1D95;
    transform: scale(1.02);
}

.upload-icon {
    font-size: 64px;
    color: #6030C1;
    margin-bottom: 20px;
}

.video-preview-container {
    background: #000;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
}

.video-preview {
    width: 100%;
    max-height: 400px;
    display: block;
}

.remove-video-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(239,68,68,0.9);
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: transform 0.2s;
}

.remove-video-btn:hover {
    transform: scale(1.1);
}

.upload-requirements {
    background: #FFF7ED;
    border-left: 4px solid #F59E0B;
    padding: 16px;
    border-radius: 8px;
    margin-top: 20px;
}

.upload-requirements ul {
    margin: 8px 0 0 0;
    padding-left: 20px;
}

.upload-requirements li {
    font-size: 14px;
    color: #92400E;
    margin-bottom: 4px;
}
</style>
@endpush

@section('content')
<div class="upload-container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-2">
            <i class="fas fa-video text-primary me-2"></i>Upload Video Produk
        </h2>
        <p class="text-muted">Upload video untuk mempromosikan produk Anda</p>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf

        {{-- Video Upload --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Video</h5>

                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('videoInput').click()">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <h5 class="fw-bold mb-2">Klik atau Drag & Drop Video</h5>
                    <p class="text-muted mb-0">Format: MP4, MOV, AVI (Maks. 100MB)</p>
                    <input type="file"
                           name="video"
                           id="videoInput"
                           accept="video/mp4,video/quicktime,video/x-msvideo"
                           class="d-none"
                           required>
                </div>

                @error('video')
                    <div class="alert alert-danger mt-2">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                    </div>
                @enderror

                {{-- Video Preview --}}
                <div id="videoPreviewWrapper" class="d-none mt-3">
                    <div class="video-preview-container">
                        <video id="videoPreview" class="video-preview" controls></video>
                        <button type="button" class="remove-video-btn" onclick="removeVideo()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Video berhasil dipilih!</strong>
                        <span id="videoInfo" class="ms-2"></span>
                    </div>
                </div>

                {{-- Requirements --}}
                <div class="upload-requirements">
                    <strong><i class="fas fa-info-circle me-2"></i>Persyaratan Video:</strong>
                    <ul>
                        <li>Durasi video minimal 3 detik, maksimal 3 menit</li>
                        <li>Ukuran file maksimal 100MB</li>
                        <li>Format: MP4, MOV, atau AVI</li>
                        <li>Resolusi minimal 720p (1280x720)</li>
                        <li>Video harus jelas menampilkan produk</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Product Selection --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Pilih Produk</h5>

                <select name="product_id" class="form-select form-select-lg @error('product_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - {{ $product->formatted_price }}
                        </option>
                    @endforeach
                </select>

                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if($products->isEmpty())
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda belum memiliki produk.
                        <a href="{{ route('penjual.products.create') }}" class="alert-link fw-bold">Buat produk terlebih dahulu</a>.
                    </div>
                @endif
            </div>
        </div>

        {{-- Video Details --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Detail Video</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Video (Opsional)</label>
                    <input type="text"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="Contoh: Unboxing & Review Produk"
                           value="{{ old('title') }}"
                           maxlength="100">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Jika kosong, akan menggunakan nama produk</small>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Jelaskan tentang produk dalam video ini..."
                              maxlength="500">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Maksimal 500 karakter</small>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex gap-3">
            <button type="submit"
                    class="btn btn-primary btn-lg px-5"
                    id="submitBtn"
                    disabled>
                <i class="fas fa-upload me-2"></i>Upload Video
            </button>
            <a href="{{ route('penjual.dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-times me-2"></i>Batal
            </a>
        </div>

        {{-- Upload Progress --}}
        <div id="uploadProgress" class="mt-4 d-none">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-spinner fa-spin text-primary me-2"></i>
                        Mengupload video...
                    </h6>
                    <div class="progress" style="height: 24px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                             id="progressBar"
                             role="progressbar"
                             style="width: 0%">
                            <span id="progressText">0%</span>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Mohon tunggu, jangan tutup halaman ini...
                    </small>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
const uploadZone = document.getElementById('uploadZone');
const videoInput = document.getElementById('videoInput');
const videoPreview = document.getElementById('videoPreview');
const videoPreviewWrapper = document.getElementById('videoPreviewWrapper');
const submitBtn = document.getElementById('submitBtn');
const uploadForm = document.getElementById('uploadForm');
const uploadProgress = document.getElementById('uploadProgress');

// Drag & Drop
uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        videoInput.files = files;
        handleVideoSelect(files[0]);
    }
});

// File input change
videoInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleVideoSelect(e.target.files[0]);
    }
});

// Handle video selection
function handleVideoSelect(file) {
    // Validate file
    const maxSize = 100 * 1024 * 1024; // 100MB
    const allowedTypes = ['video/mp4', 'video/mov', 'video/quicktime', 'video/avi', 'video/x-msvideo'];

    if (!allowedTypes.includes(file.type)) {
        alert('Format video tidak didukung. Gunakan MP4, MOV, atau AVI.');
        return;
    }

    if (file.size > maxSize) {
        alert('Ukuran file terlalu besar. Maksimal 100MB.');
        return;
    }

    // Show preview
    const url = URL.createObjectURL(file);
    videoPreview.src = url;
    uploadZone.classList.add('d-none');
    videoPreviewWrapper.classList.remove('d-none');

    // Show file info
    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
    document.getElementById('videoInfo').textContent = `${file.name} (${sizeMB} MB)`;

    // Enable submit button
    submitBtn.disabled = false;
}

// Remove video
function removeVideo() {
    videoInput.value = '';
    videoPreview.src = '';
    uploadZone.classList.remove('d-none');
    videoPreviewWrapper.classList.add('d-none');
    submitBtn.disabled = true;
}

// Form submission with progress
uploadForm.addEventListener('submit', function(e) {
    if (!videoInput.files.length) {
        e.preventDefault();
        alert('Pilih video terlebih dahulu!');
        return;
    }

    // Show progress
    submitBtn.disabled = true;
    uploadProgress.classList.remove('d-none');

    // Simulate progress (karena form submit tidak bisa track progress secara real-time)
    let progress = 0;
    const interval = setInterval(() => {
        progress += 2;
        if (progress >= 90) {
            clearInterval(interval);
        }
        updateProgress(progress);
    }, 200);
});

function updateProgress(percent) {
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    progressBar.style.width = percent + '%';
    progressText.textContent = percent + '%';
}
</script>
@endpush
@endsection

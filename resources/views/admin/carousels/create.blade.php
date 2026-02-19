@extends('layouts.admin')
@section('title', 'Tambah Carousel - Admin Cosmetiqu')
@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.carousels.index') }}">Carousel</a></li>
            <li class="breadcrumb-item active">Tambah Carousel</li>
        </ol>
    </nav>
    <h2><i class="fas fa-plus-circle"></i> Tambah Carousel Baru</h2>
</div>

<form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG. Max: 2MB. Rekomendasi: 1920x600px</small>

                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teks Tombol</label>
                                <input type="text" name="button_text" class="form-control" value="{{ old('button_text') }}" placeholder="Contoh: Belanja Sekarang">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Link Tombol</label>
                                <input type="text" name="button_link" class="form-control" value="{{ old('button_link') }}" placeholder="/products">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Urutan <span class="text-danger">*</span></label>
                                <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0" required>
                                <small class="text-muted">Semakin kecil angka, semakin awal ditampilkan</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Tips</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Judul:</strong></p>
                    <p class="small text-muted mb-3">Buat judul yang menarik dan singkat</p>

                    <p class="small mb-2"><strong>Gambar:</strong></p>
                    <p class="small text-muted mb-3">Gunakan gambar berkualitas tinggi dengan resolusi 1920x600px untuk hasil terbaik</p>

                    <p class="small mb-2"><strong>Tombol:</strong></p>
                    <p class="small text-muted mb-0">Tambahkan tombol call-to-action untuk meningkatkan engagement</p>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('preview');
        const previewDiv = document.getElementById('imagePreview');
        preview.src = reader.result;
        previewDiv.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush
@endsection

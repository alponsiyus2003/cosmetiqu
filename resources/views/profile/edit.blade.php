@extends('layouts.app')
@section('title', 'Edit Profile - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3"><i class="fas fa-user-edit text-primary"></i> Edit Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.index') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i>Informasi Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Panggilan <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', auth()->user()->full_name) }}">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', auth()->user()->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">Pilih</option>
                                        <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role != 'pengguna')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-briefcase me-2"></i>Informasi Pekerjaan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jabatan</label>
                                <input type="text" name="position" class="form-control" value="{{ old('position', auth()->user()->position) }}" placeholder="Contoh: CEO, Manager, Staff">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Departemen/Bagian</label>
                                <input type="text" name="department" class="form-control" value="{{ old('department', auth()->user()->department) }}" placeholder="Contoh: IT, Marketing, Sales">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                                <input type="text" name="education" class="form-control" value="{{ old('education', auth()->user()->education) }}" placeholder="Contoh: S1 Informatika">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Biografi</label>
                                <textarea name="bio" class="form-control" rows="4" placeholder="Ceritakan tentang diri Anda...">{{ old('bio', auth()->user()->bio) }}</textarea>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_in_about" id="show_in_about" value="1" {{ old('show_in_about', auth()->user()->show_in_about) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_about">
                                    Tampilkan di halaman "Tentang Kami"
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                                <input type="url" name="facebook" class="form-control" value="{{ old('facebook', auth()->user()->facebook) }}" placeholder="https://facebook.com/username">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                                <input type="url" name="instagram" class="form-control" value="{{ old('instagram', auth()->user()->instagram) }}" placeholder="https://instagram.com/username">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fab fa-twitter text-info"></i> Twitter</label>
                                <input type="url" name="twitter" class="form-control" value="{{ old('twitter', auth()->user()->twitter) }}" placeholder="https://twitter.com/username">
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold"><i class="fab fa-linkedin text-primary"></i> LinkedIn</label>
                                <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', auth()->user()->linkedin) }}" placeholder="https://linkedin.com/in/username">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-image me-2"></i>Foto Profile</h5>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->avatar)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Foto Saat Ini</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle shadow" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div>
                                        <p class="mb-2">Hapus foto profile saat ini?</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar" value="1">
                                            <label class="form-check-label text-danger" for="remove_avatar">
                                                Ya, hapus foto
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Upload Foto Baru</label>
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>

                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-save me-2"></i>Simpan Perubahan</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                        <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>

                        <div class="alert alert-info mt-3 mb-0">
                            <small><i class="fas fa-info-circle me-2"></i>Pastikan semua data sudah benar sebelum menyimpan.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

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

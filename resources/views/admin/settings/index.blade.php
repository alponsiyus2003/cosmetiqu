@extends('layouts.admin')
@section('title', 'Website Settings - Admin Cosmetiqu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-cog"></i> Website Settings</h2>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-globe me-2"></i>Informasi Website</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name']) }}" required>
                        @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Website</label>
                        <textarea name="site_description" class="form-control @error('site_description') is-invalid @enderror" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                        @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Logo Website</label>
                                @if($settings['site_logo'])
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" name="site_logo" class="form-control @error('site_logo') is-invalid @enderror" accept="image/*">
                                @error('site_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG, SVG. Max: 2MB</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Favicon</label>
                                @if($settings['site_favicon'])
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Current Favicon" class="img-thumbnail" style="max-height: 50px;">
                                    </div>
                                @endif
                                <input type="file" name="site_favicon" class="form-control @error('site_favicon') is-invalid @enderror" accept="image/png,image/x-icon">
                                @error('site_favicon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: PNG, ICO. Max: 1MB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Tentang Kami</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Halaman About</label>
                        <input type="text" name="about_title" class="form-control" value="{{ old('about_title', $settings['about_title']) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi About</label>
                        <textarea name="about_description" class="form-control" rows="5">{{ old('about_description', $settings['about_description']) }}</textarea>
                        <small class="text-muted">Ceritakan tentang perusahaan/toko Anda</small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-address-book me-2"></i>Kontak</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email']) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Telepon</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="contact_address" class="form-control" rows="3">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                        <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $settings['facebook']) }}" placeholder="https://facebook.com/username">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                        <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $settings['instagram']) }}" placeholder="https://instagram.com/username">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold"><i class="fab fa-twitter text-info"></i> Twitter</label>
                        <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $settings['twitter']) }}" placeholder="https://twitter.com/username">
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
                        <i class="fas fa-save me-2"></i>Update Settings
                    </button>

                    <div class="alert alert-info mb-0">
                        <small><i class="fas fa-info-circle me-2"></i>Pastikan semua informasi sudah benar sebelum menyimpan.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

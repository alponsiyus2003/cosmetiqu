@extends('layouts.app')
@section('title', 'Profile - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
                <div class="card-body text-center p-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle shadow mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle mx-auto shadow mb-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 150px; height: 150px; background: linear-gradient(135deg, #6030C1, #8B5CF6); font-size: 4rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1">{{ auth()->user()->display_name }}</h4>
                    <p class="text-muted mb-2">{{ auth()->user()->email }}</p>

                    @if(auth()->user()->position)
                        <p class="text-primary fw-semibold mb-2">{{ auth()->user()->position }}</p>
                    @endif

                    <span class="badge bg-{{ auth()->user()->role == 'admin' ? 'danger' : (auth()->user()->role == 'penjual' ? 'warning' : 'info') }} mb-3">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-2"></i>Ganti Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i>Informasi Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Nama Lengkap</div>
                        <div class="col-md-8">{{ auth()->user()->full_name ?: '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Email</div>
                        <div class="col-md-8">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Telepon</div>
                        <div class="col-md-8">{{ auth()->user()->phone ?: '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Tanggal Lahir</div>
                        <div class="col-md-8">{{ auth()->user()->birth_date ? auth()->user()->birth_date->format('d M Y') : '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Jenis Kelamin</div>
                        <div class="col-md-8">
                            @if(auth()->user()->gender)
                                {{ auth()->user()->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 fw-semibold">Alamat</div>
                        <div class="col-md-8">{{ auth()->user()->address ?: '-' }}</div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role != 'pengguna')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-briefcase me-2"></i>Informasi Pekerjaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-semibold">Jabatan</div>
                            <div class="col-md-8">{{ auth()->user()->position ?: '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-semibold">Departemen</div>
                            <div class="col-md-8">{{ auth()->user()->department ?: '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 fw-semibold">Pendidikan</div>
                            <div class="col-md-8">{{ auth()->user()->education ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->bio)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Biografi</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ auth()->user()->bio }}</p>
                    </div>
                </div>
            @endif

            @if(auth()->user()->facebook || auth()->user()->instagram || auth()->user()->twitter || auth()->user()->linkedin)
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            @if(auth()->user()->facebook)
                                <a href="{{ auth()->user()->facebook }}" target="_blank" class="btn btn-primary">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </a>
                            @endif
                            @if(auth()->user()->instagram)
                                <a href="{{ auth()->user()->instagram }}" target="_blank" class="btn btn-danger">
                                    <i class="fab fa-instagram me-2"></i>Instagram
                                </a>
                            @endif
                            @if(auth()->user()->twitter)
                                <a href="{{ auth()->user()->twitter }}" target="_blank" class="btn btn-info text-white">
                                    <i class="fab fa-twitter me-2"></i>Twitter
                                </a>
                            @endif
                            @if(auth()->user()->linkedin)
                                <a href="{{ auth()->user()->linkedin }}" target="_blank" class="btn btn-primary">
                                    <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

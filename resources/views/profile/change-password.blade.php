@extends('layouts.app')

@section('title', 'Ubah Password - Cosmetiqu')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <span class="badge bg-primary mt-2">{{ ucfirst($user->role) }}</span>
                </div>
            </div>

            <div class="list-group mt-3">
                <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user"></i> Profile Saya
                </a>
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
                <a href="{{ route('profile.change-password') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-lock"></i> Ubah Password
                </a>

                @if($user->isPengguna())
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-receipt"></i> Pesanan Saya
                    </a>
                @endif

                @if($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                    </a>
                @endif

                @if($user->isPenjual())
                    <a href="{{ route('penjual.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-store"></i> Penjual Dashboard
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lock"></i> Ubah Password
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Pastikan password baru Anda kuat dan mudah diingat. Password minimal 8 karakter.
                    </div>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Ubah Password
                        </button>
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </form>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Tips Keamanan Password
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                        <li>Jangan gunakan informasi pribadi yang mudah ditebak</li>
                        <li>Gunakan password yang berbeda untuk setiap akun</li>
                        <li>Ubah password secara berkala</li>
                        <li>Jangan bagikan password Anda kepada siapapun</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

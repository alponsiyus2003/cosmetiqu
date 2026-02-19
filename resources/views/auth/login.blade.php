@extends('layouts.app')
@section('title', 'Login - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('logo.svg') }}" alt="Cosmetiqu Logo" style="height: 60px; margin-bottom: 20px;">
                        <h1 class="text-primary fw-bold">Cosmetiqu</h1>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-primary"></i></span>
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock text-primary"></i></span>
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar Sekarang</a></p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Akun Demo untuk Testing</h6>
                    <div class="mb-2">
                        <strong>Admin:</strong>
                        <br><small class="text-muted">Email: admin@cosmetiqu.com | Password: password</small>
                    </div>
                    <div class="mb-2">
                        <strong>Penjual:</strong>
                        <br><small class="text-muted">Email: penjual1@cosmetiqu.com | Password: password</small>
                    </div>
                    <div class="mb-0">
                        <strong>Pembeli:</strong>
                        <br><small class="text-muted">Email: pembeli1@example.com | Password: password</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

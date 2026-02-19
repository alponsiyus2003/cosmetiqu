@extends('layouts.app')
@section('title', 'Tentang Kami - Cosmetiqu')
@section('content')
<div class="py-5" style="background: linear-gradient(135deg, #F5F3FF 0%, #FFF 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">{{ $about_title }}</h1>
            <div class="mx-auto" style="max-width: 800px;">
                <p class="lead text-muted">{{ $about_description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-4 text-center mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-bullseye fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Visi Kami</h5>
                    <p class="text-muted mb-0">Menjadi platform e-commerce kosmetik terpercaya yang memberikan produk berkualitas tinggi untuk semua kalangan.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-rocket fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Misi Kami</h5>
                    <p class="text-muted mb-0">Menyediakan produk kosmetik original dengan harga terjangkau dan pelayanan terbaik kepada seluruh customer.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-heart fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Nilai Kami</h5>
                    <p class="text-muted mb-0">Kepercayaan, kualitas, dan kepuasan pelanggan adalah prioritas utama dalam setiap transaksi.</p>
                </div>
            </div>
        </div>
    </div>

    @if($teamMembers->count() > 0)
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Tim Kami</h2>
            <p class="text-muted">Berkenalan dengan orang-orang di balik Cosmetiqu</p>
        </div>

        <div class="row g-4">
            @foreach($teamMembers as $member)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                @if($member->avatar)
                                    <img src="{{ $member->avatar_url }}" alt="{{ $member->display_name }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle mx-auto shadow d-flex align-items-center justify-content-center text-white fw-bold" style="width: 120px; height: 120px; background: linear-gradient(135deg, #6030C1, #8B5CF6); font-size: 3rem;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h5 class="fw-bold mb-1">{{ $member->display_name }}</h5>

                            @if($member->position)
                                <p class="text-primary mb-2 fw-semibold">{{ $member->position }}</p>
                            @endif

                            @if($member->department)
                                <p class="text-muted small mb-2">{{ $member->department }}</p>
                            @endif

                            <span class="badge bg-{{ $member->role == 'admin' ? 'danger' : 'warning' }} mb-3">
                                {{ ucfirst($member->role) }}
                            </span>

                            @if($member->bio)
                                <p class="text-muted small mb-3">{{ Str::limit($member->bio, 150) }}</p>
                            @endif

                            @if($member->education)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1"><i class="fas fa-graduation-cap me-1"></i> Pendidikan:</small>
                                    <small class="fw-semibold">{{ $member->education }}</small>
                                </div>
                            @endif

                            @if($member->email)
                                <div class="mb-2">
                                    <a href="mailto:{{ $member->email }}" class="text-muted small text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i>{{ $member->email }}
                                    </a>
                                </div>
                            @endif

                            @if($member->phone)
                                <div class="mb-3">
                                    <a href="tel:{{ $member->phone }}" class="text-muted small text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $member->phone }}
                                    </a>
                                </div>
                            @endif

                            <div class="d-flex gap-2 justify-content-center">
                                @if($member->facebook)
                                    <a href="{{ $member->facebook }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if($member->instagram)
                                    <a href="{{ $member->instagram }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if($member->twitter)
                                    <a href="{{ $member->twitter }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                                @if($member->linkedin)
                                    <a href="{{ $member->linkedin }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="fw-bold mb-3">Kenapa Memilih Kami?</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <strong>Produk Original 100%</strong> - Semua produk dijamin keasliannya
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <strong>Harga Terjangkau</strong> - Harga kompetitif dengan kualitas terbaik
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <strong>Pengiriman Cepat</strong> - Dikirim dalam 24 jam
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <strong>Customer Service 24/7</strong> - Siap membantu kapan saja
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <strong>Garansi Uang Kembali</strong> - Jaminan 100% uang kembali jika produk tidak sesuai
                    </li>
                </ul>
            </div>
            <div class="col-md-6 text-center">
                <i class="fas fa-store fa-10x text-primary" style="opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>
@endsection

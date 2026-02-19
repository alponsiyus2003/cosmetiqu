@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle fa-5x text-warning"></i>
            </div>

            <h1 class="display-1 fw-bold">404</h1>
            <h2 class="mb-4">Halaman Tidak Ditemukan</h2>

            <p class="text-muted mb-4">
                Maaf, halaman yang Anda cari tidak dapat ditemukan.
                Halaman mungkin telah dipindahkan atau dihapus.
            </p>

            <div>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->isPenjual())
                        <a href="{{ route('penjual.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-store"></i> Dashboard
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

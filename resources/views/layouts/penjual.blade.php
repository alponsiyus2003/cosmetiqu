<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Penjual Dashboard - Cosmetiqu')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #6030C1; --secondary-color: #8B5CF6; --sidebar-bg: linear-gradient(180deg, #6030C1 0%, #4e28a0 100%); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: var(--sidebar-bg); color: white; position: fixed; width: 250px; padding: 20px; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar .brand { text-align: center; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.2); margin-bottom: 20px; }
        .sidebar .brand img { height: 50px; width: auto; margin-bottom: 10px; }
        .sidebar .brand h4 { font-weight: 700; margin: 0; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 10px; margin: 5px 0; transition: all 0.3s; display: flex; align-items: center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background-color: rgba(255,255,255,0.2); transform: translateX(5px); }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; font-size: 1.1rem; }
        .main-content { margin-left: 250px; padding: 30px; min-height: 100vh; }
        .card { border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.08); margin-bottom: 20px; border-radius: 12px; transition: all 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 25px rgba(0,0,0,0.15); }
        .stat-card { padding: 25px; border-radius: 12px; color: white; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: -50%; right: -20%; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; }
        .btn-primary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none; padding: 10px 25px; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(96, 48, 193, 0.4); }
        .table { background: white; border-radius: 10px; overflow: hidden; }
        .table thead { background: linear-gradient(135deg, #6030C1, #8B5CF6); color: white; }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .badge { padding: 6px 12px; border-radius: 6px; font-weight: 600; }
        @media (max-width: 768px) { .sidebar { width: 100%; position: relative; min-height: auto; } .main-content { margin-left: 0; padding: 15px; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('logo.svg') }}" alt="Cosmetiqu Logo">
            <h4>PENJUAL</h4>
            <small>Cosmetiqu Dashboard</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}" href="{{ route('penjual.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.products.*') ? 'active' : '' }}" href="{{ route('penjual.products.index') }}">
                    <i class="fas fa-box"></i> Produk Saya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.orders.*') ? 'active' : '' }}" href="{{ route('penjual.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.reviews.*') ? 'active' : '' }}" href="{{ route('penjual.reviews.index') }}">
                    <i class="fas fa-star"></i> Review Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.videos.*') ? 'active' : '' }}"
                href="{{ route('penjual.videos.index') }}">
                    <i class="fas fa-video"></i> Video Saya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.video-comments.*') ? 'active' : '' }}"
                href="{{ route('penjual.video-comments.index') }}">
                    <i class="fas fa-comments"></i> Komentar Video
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.vouchers.*') ? 'active' : '' }}" href="{{ route('penjual.vouchers.index') }}">
                    <i class="fas fa-ticket-alt"></i> Vouchers Aktif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penjual.revenue-report.*') ? 'active' : '' }}" href="{{ route('penjual.revenue-report.index') }}">
                    <i class="fas fa-chart-line"></i> Laporan Pendapatan
                </a>
            </li>
            <hr style="border-color: rgba(255,255,255,0.3); margin: 20px 0;">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.index') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Lihat Website
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100 text-white">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <main class="main-content">
        @include('components.alert')
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

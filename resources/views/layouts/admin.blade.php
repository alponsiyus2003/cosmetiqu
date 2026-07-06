<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Cosmetiqu')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #6030C1; --secondary-color: #8B5CF6; --sidebar-bg: linear-gradient(180deg, #6030C1 0%, #4e28a0 100%); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; max-height: 100vh; overflow-y: auto; overflow-x: hidden; background: var(--sidebar-bg); color: white; position: fixed; width: 250px; padding: 20px; box-shadow: 4px 0 10px rgba(0,0,0,0.1); scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.35) transparent; }
        .sidebar::-webkit-scrollbar { width: 7px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.35); border-radius: 999px; }
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
        .stat-card.primary { background: linear-gradient(135deg, #6030C1 0%, #8B5CF6 100%); }
        .stat-card.success { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-card.info { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stat-card h6 { font-size: 0.875rem; opacity: 0.9; margin-bottom: 10px; }
        .stat-card h2 { font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; }
        .stat-card small { font-size: 0.75rem; opacity: 0.8; }
        .btn-primary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none; padding: 10px 25px; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(96, 48, 193, 0.4); }
        .table { background: white; border-radius: 10px; overflow: hidden; }
        .table thead { background: linear-gradient(135deg, #6030C1, #8B5CF6); color: white; }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .badge { padding: 6px 12px; border-radius: 6px; font-weight: 600; }
        .page-header { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #e9ecef; }
        @media (max-width: 768px) { .sidebar { width: 100%; position: relative; min-height: auto; max-height: none; overflow-y: visible; } .main-content { margin-left: 0; padding: 15px; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('logo.svg') }}" alt="Cosmetiqu Logo">
            <h4>ADMIN</h4>
            <small>Cosmetiqu Dashboard</small>
        </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="fas fa-cog"></i> Website Settings
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.carousels.*') ? 'active' : '' }}" href="{{ route('admin.carousels.index') }}">
                <i class="fas fa-images"></i> Carousel
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}">
                <i class="fas fa-ticket-alt"></i> Vouchers
            </a>
        </li>
        <hr style="border-color: rgba(255,255,255,0.3); margin: 20px 0;">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i> Kelola User
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-tags"></i> Kelola Kategori
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="fas fa-box"></i> Kelola Produk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                <i class="fas fa-star"></i> Kelola Review
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}"
            href="{{ route('admin.videos.index') }}">
                <i class="fas fa-video"></i> Kelola Video
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.video-comments.*') ? 'active' : '' }}"
            href="{{ route('admin.video-comments.index') }}">
                <i class="fas fa-comments"></i> Komentar Video
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-cart"></i> Kelola Pesanan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.revenue-report.*') ? 'active' : '' }}" href="{{ route('admin.revenue-report.index') }}">
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

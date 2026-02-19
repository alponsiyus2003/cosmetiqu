<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cosmetiqu - Toko Kosmetik Online')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6030C1;
            --primary-dark: #4e28a0;
            --secondary-color: #8B5CF6;
            --accent-color: #C4B5FD;
            --light-purple: #F5F3FF;
            --dark-text: #2D3436;
            --light-text: #636E72;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: var(--dark-text); background-color: #F8F9FA; }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 1rem 0; background: white !important; }
        .navbar-brand { font-weight: 700; color: var(--primary-color) !important; font-size: 1.75rem; letter-spacing: -0.5px; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; }
        .navbar-brand:hover { transform: scale(1.05); }
        .navbar-brand img { height: 40px; width: auto; }
        .nav-link { font-weight: 500; color: var(--dark-text) !important; padding: 0.5rem 1rem !important; transition: all 0.3s ease; position: relative; }
        .nav-link:hover { color: var(--primary-color) !important; transform: translateY(-2px); }
        .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 30px; height: 3px; background: var(--primary-color); border-radius: 10px; }
        .btn-primary { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border: none; padding: 0.625rem 1.5rem; font-weight: 600; border-radius: 10px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(96, 48, 193, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(96, 48, 193, 0.4); background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-color) 100%); }
        .btn-outline-primary { border: 2px solid var(--primary-color); color: var(--primary-color); font-weight: 600; border-radius: 10px; transition: all 0.3s ease; }
        .btn-outline-primary:hover { background: var(--primary-color); color: white; transform: translateY(-2px); border-color: var(--primary-color); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; overflow: hidden; }
        .card:hover { transform: translateY(-8px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .card-img-top { transition: transform 0.3s ease; }
        .card:hover .card-img-top { transform: scale(1.05); }
        .product-image { height: 250px; object-fit: cover; }
        .badge { padding: 0.5rem 0.75rem; font-weight: 600; border-radius: 8px; }
        .badge.bg-primary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important; }
        .hero-section { background: linear-gradient(135deg, var(--light-purple) 0%, #FFF 100%); padding: 80px 0; position: relative; overflow: hidden; }
        .hero-section::before { content: ''; position: absolute; top: -50%; right: -10%; width: 500px; height: 500px; background: var(--accent-color); border-radius: 50%; opacity: 0.3; z-index: 0; }
        .hero-section .container { position: relative; z-index: 1; }
        .alert { border: none; border-radius: 12px; padding: 1rem 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .alert-success { background: linear-gradient(135deg, #00B894 0%, #00CEC9 100%); color: white; }
        .alert-danger { background: linear-gradient(135deg, #D63031 0%, #E17055 100%); color: white; }
        .alert-info { background: linear-gradient(135deg, #0984E3 0%, #74B9FF 100%); color: white; }
        .alert-warning { background: linear-gradient(135deg, #FDCB6E 0%, #E17055 100%); color: white; }
        footer { background: linear-gradient(135deg, var(--dark-text) 0%, #34495E 100%); color: white; padding: 3rem 0 1.5rem; margin-top: 80px; }
        footer a { color: var(--accent-color); text-decoration: none; transition: all 0.3s ease; }
        footer a:hover { color: white; }
        .text-primary { color: var(--primary-color) !important; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .spinner-border { border-color: var(--primary-color); border-right-color: transparent; }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }
        @media (max-width: 768px) { .hero-section { padding: 50px 0; } .display-4 { font-size: 2rem; } .product-image { height: 200px; } }
    </style>
    @stack('styles')
</head>
<body>
    @include('components.navbar')
    <main>
        @include('components.alert')
        @yield('content')
    </main>
    <footer class="text-center">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                        <img src="{{ asset('logo.svg') }}" alt="Cosmetiqu Logo" style="height: 40px;">
                        <h5 class="mb-0">Cosmetiqu</h5>
                    </div>
                    <p class="text-muted">Toko kosmetik online terpercaya dengan produk berkualitas.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Produk</a></li>
                        @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        @endguest
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">Follow Us</h6>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="#"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#"><i class="fab fa-twitter fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <p class="mb-0">&copy; {{ date('Y') }} Cosmetiqu. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

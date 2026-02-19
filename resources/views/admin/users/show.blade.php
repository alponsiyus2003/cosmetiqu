@extends('layouts.admin')

@section('title', 'Detail User - Admin Cosmetiqu')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2>
            <i class="fas fa-user"></i> Detail User: {{ $user->name }}
        </h2>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- User Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($user->avatar)
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; font-size: 4rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <h4>{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'penjual' ? 'warning' : 'info') }} mb-3">
                    {{ ucfirst($user->role) }}
                </span>

                <hr>

                <div class="text-start">
                    <p class="mb-2">
                        <i class="fas fa-phone text-muted"></i>
                        <strong>Phone:</strong> {{ $user->phone ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt text-muted"></i>
                        <strong>Alamat:</strong><br>
                        {{ $user->address ?? '-' }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar text-muted"></i>
                        <strong>Bergabung:</strong><br>
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity & Stats -->
    <div class="col-md-8">
        @if($user->role == 'penjual')
            <!-- Stats Penjual -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-box fa-2x text-primary mb-2"></i>
                            <h3 class="mb-0">{{ $user->products->count() }}</h3>
                            <small class="text-muted">Total Produk</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                            <h3 class="mb-0">{{ $user->products->sum(function($product) { return $product->orderItems->count(); }) }}</h3>
                            <small class="text-muted">Total Penjualan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-check-circle fa-2x text-warning mb-2"></i>
                            <h3 class="mb-0">{{ $user->products->where('is_active', true)->count() }}</h3>
                            <small class="text-muted">Produk Aktif</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-box"></i> Produk Terbaru
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($user->products->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">Belum ada produk</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->products->take(5) as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        @elseif($user->role == 'pengguna')
            <!-- Stats Pengguna -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                            <h3 class="mb-0">{{ $user->orders->count() }}</h3>
                            <small class="text-muted">Total Pesanan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h3 class="mb-0">{{ $user->orders->where('status', 'delivered')->count() }}</h3>
                            <small class="text-muted">Pesanan Selesai</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-cart"></i> Pesanan Terbaru
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($user->orders->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">Belum ada pesanan</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->take(5) as $order)
                                        <tr>
                                            <td>#{{ $order->order_number }}</td>
                                            <td>{{ $order->orderItems->count() }} items</td>
                                            <td>{{ $order->formatted_total }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status_badge }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        @else
            <!-- Admin Info -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-shield fa-5x text-danger mb-3"></i>
                    <h4>Administrator</h4>
                    <p class="text-muted mb-0">User ini memiliki akses penuh ke sistem.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

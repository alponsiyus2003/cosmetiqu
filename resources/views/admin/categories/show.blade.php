@extends('layouts.admin')

@section('title', 'Detail Kategori - Admin Cosmetiqu')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2>
            <i class="fas fa-tag"></i> {{ $category->name }}
        </h2>
        <div>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informasi Kategori</h6>
            </div>
            <div class="card-body">
                @if($category->image)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid rounded mb-3">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fas fa-tag fa-5x text-muted"></i>
                    </div>
                @endif

                <div class="mb-3">
                    <small class="text-muted">Status</small>
                    <div>
                        <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Slug</small>
                    <p class="mb-0">{{ $category->slug }}</p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Deskripsi</small>
                    <p class="mb-0">{{ $category->description ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Total Produk</small>
                    <h4 class="mb-0">{{ $category->products->count() }}</h4>
                </div>

                <div class="mb-0">
                    <small class="text-muted">Dibuat pada</small>
                    <p class="mb-0">{{ $category->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-box"></i> Produk dalam Kategori ({{ $category->products->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                @if($category->products->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Belum ada produk dalam kategori ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Penjual</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <span>{{ $product->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $product->seller->name }}</td>
                                        <td>{{ $product->formatted_price }}</td>
                                        <td>
                                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

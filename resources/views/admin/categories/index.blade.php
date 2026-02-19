@extends('layouts.admin')

@section('title', 'Kelola Kategori - Admin Cosmetiqu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-tags"></i> Kelola Kategori
    </h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<!-- Categories Grid -->
<div class="row">
    @forelse($categories as $category)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($category->image)
                    <img src="{{ $category->image_url }}" class="card-img-top" alt="{{ $category->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-tag fa-4x text-muted"></i>
                    </div>
                @endif

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $category->name }}</h5>
                        <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <p class="text-muted small mb-2">
                        <i class="fas fa-box"></i> {{ $category->products_count }} Produk
                    </p>

                    @if($category->description)
                        <p class="card-text text-muted small">{{ Str::limit($category->description, 100) }}</p>
                    @endif
                </div>

                <div class="card-footer bg-white">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $category->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>

                    <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tags fa-5x text-muted mb-3"></i>
                    <h4>Belum Ada Kategori</h4>
                    <p class="text-muted">Mulai dengan menambahkan kategori produk pertama Anda.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($categories->hasPages())
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
function confirmDelete(categoryId) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki produk tidak dapat dihapus.')) {
        document.getElementById('delete-form-' + categoryId).submit();
    }
}
</script>
@endpush

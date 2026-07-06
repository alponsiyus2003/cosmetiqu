@extends('layouts.admin')
@section('title', 'Kelola Komentar Video - Admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            <i class="fas fa-comments text-primary me-2"></i>Kelola Komentar Video
        </h2>
        <p class="text-muted mb-0">Moderasi komentar dari semua video produk</p>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-comments fa-lg text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Komentar</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-day fa-lg text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Hari Ini</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['today'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-week fa-lg text-info"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Minggu Ini</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['this_week'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Filter Video</label>
                    <select name="video_id" class="form-select">
                        <option value="">Semua Video</option>
                        @foreach($videos as $video)
                            <option value="{{ $video->id }}" {{ request('video_id') == $video->id ? 'selected' : '' }}>
                                {{ $video->title ?? 'Video #' . $video->id }} - {{ $video->product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Cari Komentar</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari komentar atau nama user..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Comments Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($comments->count() > 0)
                <form id="bulkDeleteForm" method="POST" action="{{ route('admin.video-comments.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                            <label class="form-check-label fw-semibold" for="selectAll">
                                Pilih Semua
                            </label>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash me-2"></i>Hapus Terpilih
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="50"></th>
                                    <th>User</th>
                                    <th>Komentar</th>
                                    <th>Video</th>
                                    <th>Waktu</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comments as $comment)
                                    <tr>
                                        <td class="px-4">
                                            <input type="checkbox" name="comment_ids[]"
                                                   value="{{ $comment->id }}"
                                                   class="form-check-input comment-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                     style="width: 36px; height: 36px; font-size: 14px; font-weight: bold;">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $comment->user->name }}</div>
                                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">{{ Str::limit($comment->comment, 100) }}</p>
                                        </td>
                                        <td>
                                            <a href="{{ route('videos.show', $comment->video->id) }}"
                                               class="text-decoration-none"
                                               target="_blank">
                                                {{ $comment->video->title ?? 'Video #' . $comment->video->id }}
                                                <br>
                                                <small class="text-muted">{{ $comment->video->product->name }}</small>
                                            </a>
                                        </td>
                                        <td>
                                            <small>{{ $comment->created_at->format('d M Y, H:i') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.video-comments.destroy', $comment->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                @if($comments->hasPages())
                    <div class="p-3">
                        {{ $comments->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h5 class="text-muted">Tidak Ada Komentar</h5>
                    <p class="text-muted">Belum ada komentar di video produk.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Select all checkboxes
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    toggleBulkDeleteBtn();
});

// Show/hide bulk delete button
document.querySelectorAll('.comment-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkDeleteBtn);
});

function toggleBulkDeleteBtn() {
    const checked = document.querySelectorAll('.comment-checkbox:checked').length;
    const btn = document.getElementById('bulkDeleteBtn');
    btn.style.display = checked > 0 ? 'block' : 'none';
}

// Confirm bulk delete
document.getElementById('bulkDeleteForm').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.comment-checkbox:checked').length;
    if (checked === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 komentar untuk dihapus');
        return false;
    }
    if (!confirm(`Yakin ingin menghapus ${checked} komentar?`)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush
@endsection

@extends('layouts.app')
@section('title', 'Detail Pesanan - Cosmetiqu')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fw-bold"><i class="fas fa-receipt text-primary me-2"></i>Detail Pesanan</h1>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">

            {{-- Order Status Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Info Pesanan</h5>
                        <span class="badge bg-{{ $order->status_badge }} fs-6">{{ $order->status_label }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Nomor Pesanan</small>
                            <strong class="fs-5">{{ $order->order_number }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Tanggal Pesanan</small>
                            <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Metode Pembayaran</small>
                            <strong>{{ $order->payment_method }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Status Pembayaran</small>
                            <span class="badge bg-{{ $order->payment_status_badge }} fs-6">{{ $order->payment_status_label }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Nomor Telepon</small>
                            <strong>{{ $order->phone }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Status Pesanan</small>
                            <span class="badge bg-{{ $order->status_badge }} fs-6">{{ $order->status_label }}</span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block">Alamat Pengiriman</small>
                        <strong>{{ $order->shipping_address }}</strong>
                    </div>
                    @if($order->notes)
                        <div class="mt-3">
                            <small class="text-muted d-block">Catatan</small>
                            <strong>{{ $order->notes }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-box me-2"></i>Produk yang Dipesan</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->orderItems as $item)
                        <div class="border-bottom p-4">
                            {{-- Product Info --}}
                            <div class="d-flex align-items-start mb-3">
                                @if($item->product->image)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                         class="rounded shadow-sm flex-shrink-0"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                                         style="width: 90px; height: 90px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                    <small class="text-muted d-block"><i class="fas fa-store me-1"></i>{{ $item->seller->name }}</small>
                                    <small class="text-muted d-block">{{ $item->formatted_price }} x {{ $item->quantity }}</small>
                                    <strong class="text-primary">{{ $item->formatted_subtotal }}</strong>
                                </div>
                            </div>

                            {{-- Review Section (hanya jika order delivered) --}}
                            @if($order->status == 'delivered')
                                @php
                                    $review = \App\Models\Review::with(['media', 'replies.user'])
                                                ->where('user_id', auth()->id())
                                                ->where('product_id', $item->product_id)
                                                ->where('order_id', $order->id)
                                                ->first();
                                @endphp

                                @if($review)
                                    {{-- Show Existing Review --}}
                                    <div class="mt-3 p-3 rounded" style="background: #F5F3FF; border-left: 4px solid #6030C1;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <small class="text-muted fw-semibold d-block mb-1">
                                                    <i class="fas fa-star text-warning me-1"></i>Review Anda
                                                </small>
                                                <div class="d-flex align-items-center gap-2">
                                                    {!! $review->stars_html !!}
                                                    <span class="badge bg-warning text-dark">{{ $review->rating_label }}</span>
                                                    @if($review->is_verified_purchase)
                                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('reviews.edit', $review->id) }}"
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('reviews.destroy', $review->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus review ini?')"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        @if($review->comment)
                                            <p class="mb-3 text-dark">{{ $review->comment }}</p>
                                        @endif

                                        {{-- Review Media (Photo & Video) --}}
                                        @if($review->media->count() > 0)
                                            <div class="mb-3">
                                                <small class="text-muted fw-semibold d-block mb-2">
                                                    <i class="fas fa-photo-video me-1"></i>
                                                    Foto & Video ({{ $review->media->count() }})
                                                </small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($review->media as $media)
                                                        @if($media->is_image)
                                                            <div class="position-relative review-media-thumb"
                                                                 onclick="openMediaModal('{{ $media->url }}', 'image')"
                                                                 style="cursor: pointer;">
                                                                <img src="{{ $media->url }}"
                                                                     class="rounded shadow-sm"
                                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded"
                                                                     style="background: rgba(0,0,0,0); transition: background 0.2s;"
                                                                     onmouseover="this.style.background='rgba(0,0,0,0.3)'"
                                                                     onmouseout="this.style.background='rgba(0,0,0,0)'">
                                                                    <i class="fas fa-search-plus text-white" style="opacity: 0; transition: opacity 0.2s;"
                                                                       onmouseover="this.style.opacity='1'"
                                                                       onmouseout="this.style.opacity='0'"></i>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="position-relative review-media-thumb"
                                                                 onclick="openMediaModal('{{ $media->url }}', 'video')"
                                                                 style="cursor: pointer; width: 80px; height: 80px;">
                                                                <video src="{{ $media->url }}"
                                                                       class="rounded shadow-sm w-100 h-100"
                                                                       style="object-fit: cover;"></video>
                                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded"
                                                                     style="background: rgba(0,0,0,0.4);">
                                                                    <i class="fas fa-play-circle fa-2x text-white"></i>
                                                                </div>
                                                                <span class="position-absolute bottom-0 start-0 badge bg-dark m-1"
                                                                      style="font-size: 9px;">VIDEO</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Replies --}}
                                        @if($review->replies->count() > 0)
                                            <div class="mt-3">
                                                <small class="text-muted fw-semibold d-block mb-2">
                                                    <i class="fas fa-reply me-1"></i>Balasan
                                                </small>
                                                @foreach($review->replies as $reply)
                                                    <div class="d-flex p-2 rounded mb-2" style="background: white;">
                                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2 fw-bold flex-shrink-0"
                                                             style="width: 32px; height: 32px; font-size: 12px; background: linear-gradient(135deg, #6030C1, #8B5CF6);">
                                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <strong class="small">{{ $reply->user->name }}</strong>
                                                                <span class="badge bg-{{ $reply->role_badge }}" style="font-size: 10px;">{{ $reply->role_label }}</span>
                                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                            </div>
                                                            <p class="mb-0 small">{{ $reply->reply }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- No Review Yet --}}
                                    <div class="mt-3">
                                        <a href="{{ route('reviews.create', [$order->id, $item->product_id]) }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-star me-2"></i>Beri Review & Unboxing
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Payment Summary --}}
            <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    @php
                        $subtotal = $order->orderItems->sum('subtotal');
                        $shipping = 20000;
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkos Kirim</span>
                        <strong>Rp {{ number_format($shipping, 0, ',', '.') }}</strong>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-success">
                                <i class="fas fa-ticket-alt me-1"></i>Diskon Voucher
                                @if($order->voucher)
                                    <small class="d-block text-muted">{{ $order->voucher->code }}</small>
                                @endif
                            </span>
                            <strong class="text-success">- {{ $order->formatted_discount }}</strong>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold">Total</h5>
                        <h4 class="text-primary fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            {{-- Cancel Button --}}
            @if($order->status == 'pending')
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times-circle me-2"></i>Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Media Modal --}}
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2" id="mediaModalBody">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openMediaModal(url, type) {
    const body = document.getElementById('mediaModalBody');
    if (type === 'image') {
        body.innerHTML = `<img src="${url}" class="img-fluid rounded" style="max-height: 80vh;">`;
    } else {
        body.innerHTML = `<video src="${url}" class="w-100 rounded" style="max-height: 80vh;" controls autoplay></video>`;
    }
    new bootstrap.Modal(document.getElementById('mediaModal')).show();
}
</script>
@endpush
@endsection

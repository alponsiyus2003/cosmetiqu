@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin Cosmetiqu')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
            <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
        </ol>
    </nav>
    <h2>
        <i class="fas fa-shopping-bag"></i> Pesanan #{{ $order->order_number }}
    </h2>
</div>

<!-- Order Status & Payment Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-truck"></i> Update Status Pesanan
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Status Saat Ini</label>
                        <div>
                            <span class="badge bg-{{ $order->status_badge }} fs-6">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ubah Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-money-bill"></i> Update Status Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Status Saat Ini</label>
                        <div>
                            <span class="badge bg-{{ $order->payment_status_badge }} fs-6">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ubah Status <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-select" required>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Update Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Order Items -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-box"></i> Produk yang Dipesan ({{ $order->orderItems->count() }} items)
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Penjual</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $item->seller->name }}
                                        <br>
                                        <small class="text-muted">{{ $item->seller->email }}</small>
                                    </td>
                                    <td>{{ $item->formatted_price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>{{ $item->formatted_subtotal }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td><strong class="text-primary">{{ $order->formatted_total }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-user"></i> Informasi Customer
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer</h6>
                        <p class="mb-1">{{ $order->user->name }}</p>
                        <p class="mb-1 text-muted">{{ $order->user->email }}</p>
                        <p class="mb-0 text-muted">{{ $order->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Alamat Pengiriman</h6>
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                @if($order->notes)
                    <hr>
                    <h6>Catatan Customer</h6>
                    <p class="mb-0 text-muted">{{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-receipt"></i> Ringkasan Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Order Number</small>
                    <h6>#{{ $order->order_number }}</h6>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Tanggal Pesanan</small>
                    <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Metode Pembayaran</small>
                    <p class="mb-0">{{ $order->payment_method }}</p>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted">Status Pesanan</small>
                    <div>
                        <span class="badge bg-{{ $order->status_badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Status Pembayaran</small>
                    <div>
                        <span class="badge bg-{{ $order->payment_status_badge }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Total Items</span>
                    <strong>{{ $order->orderItems->count() }} items</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <h6>Total Pembayaran</h6>
                    <h5 class="text-primary mb-0">{{ $order->formatted_total }}</h5>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-history"></i> Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Pesanan Dibuat</small>
                    <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                @if($order->payment_status == 'paid')
                    <div class="mb-3">
                        <small class="text-success">Pembayaran Dikonfirmasi</small>
                        <p class="mb-0">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif

                @if($order->status == 'delivered')
                    <div class="mb-0">
                        <small class="text-success">Pesanan Selesai</small>
                        <p class="mb-0">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif

                @if($order->status == 'cancelled')
                    <div class="mb-0">
                        <small class="text-danger">Pesanan Dibatalkan</small>
                        <p class="mb-0">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sellerId = auth()->id();

        $data = [
            'totalProducts' => Product::where('user_id', $sellerId)->count(),
            'activeProducts' => Product::where('user_id', $sellerId)->where('is_active', true)->count(),
            'totalStock' => Product::where('user_id', $sellerId)->sum('stock'),
            'lowStockProducts' => Product::where('user_id', $sellerId)
                ->where('stock', '<=', 10)
                ->where('stock', '>', 0)
                ->count(),
            'outOfStockProducts' => Product::where('user_id', $sellerId)->where('stock', 0)->count(),
            'totalOrders' => OrderItem::where('seller_id', $sellerId)->count(),
            'totalRevenue' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('payment_status', 'paid');
                })
                ->sum('subtotal'),
            'recentOrders' => OrderItem::with(['order.user', 'product'])
                ->where('seller_id', $sellerId)
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('penjual.dashboard', $data);
    }
}

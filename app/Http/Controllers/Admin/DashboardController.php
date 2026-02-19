<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::where('role', 'pengguna')->count(),
            'totalPenjual' => User::where('role', 'penjual')->count(),
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'recentOrders' => Order::with(['user', 'orderItems.product'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}

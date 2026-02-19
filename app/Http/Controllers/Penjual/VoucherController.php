<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        // Penjual hanya lihat voucher yang bisa digunakan untuk produknya
        $vouchers = Voucher::withCount('usages')
                          ->where('is_active', true)
                          ->latest()
                          ->paginate(10);

        return view('penjual.vouchers.index', compact('vouchers'));
    }

    public function show(Voucher $voucher)
    {
        $voucher->load(['usages.user', 'usages.order']);

        // Filter usage untuk order yang mengandung produk penjual
        $myUsages = $voucher->usages->filter(function($usage) {
            return $usage->order->orderItems->where('seller_id', auth()->id())->count() > 0;
        });

        return view('penjual.vouchers.show', compact('voucher', 'myUsages'));
    }
}

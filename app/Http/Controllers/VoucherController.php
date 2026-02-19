<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan!'
            ], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid atau sudah expired!'
            ], 400);
        }

        if (!$voucher->canBeUsedByUser(auth()->id())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas penggunaan voucher ini!'
            ], 400);
        }

        if ($request->subtotal < $voucher->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->min_purchase, 0, ',', '.')
            ], 400);
        }

        $discount = $voucher->calculateDiscount($request->subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diterapkan!',
            'data' => [
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'discount' => $discount,
                'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            ]
        ]);
    }
}

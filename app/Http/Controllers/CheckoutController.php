<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts = Cart::with(['product.seller', 'product.category'])
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong!');
        }

        // Calculate total
        $subtotal = $carts->sum(function($cart) {
            return $cart->product->price * $cart->quantity;
        });

        $shippingCost = 20000; // Fixed shipping cost for now
        $total = $subtotal + $shippingCost;

        return view('checkout.index', compact('carts', 'subtotal', 'shippingCost', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'voucher_code' => 'nullable|string',
        ]);

        $carts = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong!');
        }

        DB::beginTransaction();

        try {
            // Check stock availability
            foreach ($carts as $cart) {
                if ($cart->product->stock < $cart->quantity) {
                    throw new \Exception('Stok produk ' . $cart->product->name . ' tidak mencukupi!');
                }
            }

            // Calculate subtotal
            $subtotal = 0;
            foreach ($carts as $cart) {
                if ($cart->product->stock < $cart->quantity) {
                    return back()->with('error', "Stok {$cart->product->name} tidak mencukupi!");
                }
                $subtotal += $cart->product->price * $cart->quantity;
            }

            $shippingCost = 20000;
            $discountAmount = 0;
            $voucherId = null;

            // Apply voucher if provided
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', strtoupper($request->voucher_code))->first();

                if ($voucher && $voucher->isValid() && $voucher->canBeUsedByUser(auth()->id())) {
                    if ($subtotal >= $voucher->min_purchase) {
                        $discountAmount = $voucher->calculateDiscount($subtotal);
                        $voucherId = $voucher->id;
                    }
                }
            }

            $total = $subtotal + $shippingCost - $discountAmount;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'CSM' . date('Ymd') . strtoupper(Str::random(6)),
                'total_amount' => $total,
                'voucher_id' => $voucherId,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($carts as $cart) {
                $order->orderItems()->create([
                    'product_id' => $cart->product_id,
                    'seller_id' => $cart->product->user_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'subtotal' => $cart->product->price * $cart->quantity,
                ]);

                // Reduce stock
                $cart->product->decrement('stock', $cart->quantity);
            }

            // Record voucher usage
            if ($voucherId) {
                VoucherUsage::create([
                    'voucher_id' => $voucherId,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'discount_amount' => $discountAmount,
                ]);
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}

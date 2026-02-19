<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
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

        $total = $carts->sum(function($cart) {
            return $cart->product->price * $cart->quantity;
        });

        return view('cart.index', compact('carts', 'total'));
    }

public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);

    // Check stock
    if ($product->stock < $request->quantity) {
        return back()->with('error', 'Stok produk tidak mencukupi!');
    }

    // Check if product already in cart
    $cart = Cart::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->first();

    if ($cart) {
        // Update quantity
        $newQuantity = $cart->quantity + $request->quantity;

        if ($product->stock < $newQuantity) {
            return back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $cart->update(['quantity' => $newQuantity]);

        return back()->with('success', 'Jumlah produk di keranjang berhasil diupdate!');
    } else {
        // Create new cart item
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
}

    public function update(Request $request, Cart $cart)
    {
        // Check ownership
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check stock
        if ($cart->product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function destroy(Cart $cart)
    {
        // Check ownership
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}

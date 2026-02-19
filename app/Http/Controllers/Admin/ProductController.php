<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'seller']);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by seller
        if ($request->has('seller') && $request->seller != '') {
            $query->where('user_id', $request->seller);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::all();
        $sellers = User::where('role', 'penjual')->get();

        return view('admin.products.index', compact('products', 'categories', 'sellers'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'seller', 'orderItems.order']);
        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah ada dalam pesanan!');
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}

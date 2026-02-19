<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderItem::with(['order.user', 'product'])
            ->where('seller_id', auth()->id());

        // Filter by order status
        if ($request->has('status') && $request->status != '') {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $orderItems = $query->latest()->paginate(15);

        return view('penjual.orders.index', compact('orderItems'));
    }

    public function show(Order $order)
    {
        // Check if seller has items in this order
        $hasItems = $order->orderItems()->where('seller_id', auth()->id())->exists();

        if (!$hasItems) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['user', 'orderItems' => function($query) {
            $query->where('seller_id', auth()->id())->with('product');
        }]);

        return view('penjual.orders.show', compact('order'));
    }
}

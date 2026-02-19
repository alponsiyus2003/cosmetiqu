<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewMedia;
use App\Models\ReviewReply;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create($orderId, $productId)
    {
        $order = Order::where('user_id', auth()->id())
                      ->where('status', 'delivered')
                      ->findOrFail($orderId);

        $product = Product::findOrFail($productId);

        $orderItem = $order->orderItems()->where('product_id', $productId)->first();
        if (!$orderItem) {
            abort(404);
        }

        $existingReview = Review::where('user_id', auth()->id())
                                ->where('product_id', $productId)
                                ->where('order_id', $orderId)
                                ->first();

        if ($existingReview) {
            return redirect()->route('orders.show', $orderId)
                             ->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        return view('reviews.create', compact('order', 'product'));
    }

    public function store(Request $request, $orderId, $productId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200',
        ]);

        $order = Order::where('user_id', auth()->id())
                      ->where('status', 'delivered')
                      ->findOrFail($orderId);

        $orderItem = $order->orderItems()->where('product_id', $productId)->first();
        if (!$orderItem) {
            abort(404);
        }

        $review = Review::create([
            'user_id'               => auth()->id(),
            'product_id'            => $productId,
            'order_id'              => $orderId,
            'rating'                => $request->rating,
            'comment'               => $request->comment,
            'is_approved'           => true,
            'is_verified_purchase'  => true,
        ]);

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                $path = $file->store('reviews/' . $review->id, 'public');

                ReviewMedia::create([
                    'review_id' => $review->id,
                    'file_path' => $path,
                    'type'      => $type,
                    'order'     => $index,
                ]);
            }
        }

        return redirect()->route('orders.show', $orderId)
                         ->with('success', 'Review berhasil ditambahkan! Terima kasih atas ulasan Anda.');
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }
        $review->load(['media', 'product', 'order']);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
            'media.*'       => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200',
            'delete_media'  => 'nullable|array',
            'delete_media.*'=> 'integer|exists:review_media,id',
        ]);

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        // Delete selected media
        if ($request->has('delete_media')) {
            foreach ($request->delete_media as $mediaId) {
                $media = ReviewMedia::where('id', $mediaId)
                                    ->where('review_id', $review->id)
                                    ->first();
                if ($media) {
                    Storage::disk('public')->delete($media->file_path);
                    $media->delete();
                }
            }
        }

        // Add new media
        if ($request->hasFile('media')) {
            $currentCount = $review->media()->count();
            foreach ($request->file('media') as $index => $file) {
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                $path = $file->store('reviews/' . $review->id, 'public');

                ReviewMedia::create([
                    'review_id' => $review->id,
                    'file_path' => $path,
                    'type'      => $type,
                    'order'     => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('orders.show', $review->order_id)
                         ->with('success', 'Review berhasil diupdate!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $orderId = $review->order_id;

        // Delete media files
        foreach ($review->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $review->delete();

        return redirect()->route('orders.show', $orderId)
                         ->with('success', 'Review berhasil dihapus!');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        // Only admin or product seller can reply
        if (!$user->isAdmin() && !($user->isPenjual() && $review->product->user_id == $user->id)) {
            abort(403);
        }

        ReviewReply::create([
            'review_id' => $review->id,
            'user_id'   => $user->id,
            'reply'     => $request->reply,
            'role'      => $user->isAdmin() ? 'admin' : 'penjual',
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function deleteReply(ReviewReply $reply)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $reply->user_id !== $user->id) {
            abort(403);
        }

        $reply->delete();
        return back()->with('success', 'Balasan berhasil dihapus!');
    }
}

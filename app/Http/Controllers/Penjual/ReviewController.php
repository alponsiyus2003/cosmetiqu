<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'media', 'replies'])
                       ->whereHas('product', function($q) {
                           $q->where('user_id', auth()->id());
                       })
                       ->where('is_approved', true)
                       ->latest();

        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        if ($request->has('replied') && $request->replied != '') {
            if ($request->replied == 'yes') {
                $query->whereHas('replies');
            } else {
                $query->whereDoesntHave('replies');
            }
        }

        $reviews = $query->paginate(10);

        $stats = [
            'total'       => Review::whereHas('product', function($q) { $q->where('user_id', auth()->id()); })->where('is_approved', true)->count(),
            'replied'     => Review::whereHas('product', function($q) { $q->where('user_id', auth()->id()); })->whereHas('replies')->count(),
            'not_replied' => Review::whereHas('product', function($q) { $q->where('user_id', auth()->id()); })->whereDoesntHave('replies')->count(),
            'avg_rating'  => Review::whereHas('product', function($q) { $q->where('user_id', auth()->id()); })->avg('rating') ?? 0,
        ];

        return view('penjual.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review)
    {
        // Make sure it's penjual's product
        if ($review->product->user_id !== auth()->id()) {
            abort(403);
        }

        $review->load(['user', 'product', 'order', 'media', 'replies.user']);
        return view('penjual.reviews.show', compact('review'));
    }

    public function reply(Request $request, Review $review)
    {
        if ($review->product->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        ReviewReply::create([
            'review_id' => $review->id,
            'user_id'   => auth()->id(),
            'reply'     => $request->reply,
            'role'      => 'penjual',
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function deleteReply(ReviewReply $reply)
    {
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }
        $reply->delete();
        return back()->with('success', 'Balasan berhasil dihapus!');
    }
}

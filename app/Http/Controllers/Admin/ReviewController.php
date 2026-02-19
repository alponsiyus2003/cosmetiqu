<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'media', 'replies'])
                       ->latest();

        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_approved', $request->status == 'approved');
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $query->paginate(15);

        $stats = [
            'total'    => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending'  => Review::where('is_approved', false)->count(),
            'with_media' => Review::whereHas('media')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product', 'order', 'media', 'replies.user']);
        return view('admin.reviews.show', compact('review'));
    }

    public function toggleApproval(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $status = $review->is_approved ? 'disetujui' : 'disembunyikan';
        return back()->with('success', "Review berhasil {$status}!");
    }

    public function destroy(Review $review)
    {
        foreach ($review->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        $review->delete();
        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Review berhasil dihapus!');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        ReviewReply::create([
            'review_id' => $review->id,
            'user_id'   => auth()->id(),
            'reply'     => $request->reply,
            'role'      => 'admin',
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function deleteReply(ReviewReply $reply)
    {
        $reply->delete();
        return back()->with('success', 'Balasan berhasil dihapus!');
    }
}

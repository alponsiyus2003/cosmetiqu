<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\ProductVideoComment;
use App\Models\ProductVideo;
use Illuminate\Http\Request;

class VideoCommentController extends Controller
{
    /**
     * Display all video comments
     */
    public function index(Request $request)
    {
        $query = ProductVideoComment::with(['video.product', 'user']);

        // Filter by video
        if ($request->video_id) {
            $query->where('product_video_id', $request->video_id);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('comment', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $comments = $query->latest()->paginate(20);

        $stats = [
            'total' => ProductVideoComment::count(),
            'today' => ProductVideoComment::whereDate('created_at', today())->count(),
            'this_week' => ProductVideoComment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        $videos = ProductVideo::with('product')->orderBy('created_at', 'desc')->limit(50)->get();

        return view('admin.video-comments.index', compact('comments', 'stats', 'videos'));
    }

    /**
     * Delete a comment
     */
    public function destroy(ProductVideoComment $comment)
    {
        try {
            $comment->delete();
            return back()->with('success', 'Komentar berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus komentar: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete comments
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:product_video_comments,id',
        ]);

        try {
            ProductVideoComment::whereIn('id', $request->comment_ids)->delete();
            return back()->with('success', count($request->comment_ids) . ' komentar berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus komentar: ' . $e->getMessage());
        }
    }
}

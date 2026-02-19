<?php

namespace App\Http\Controllers;

use App\Models\ProductVideo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductVideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    // Video feed (Shopee style)
    public function index()
    {
        $videos = ProductVideo::with(['product.seller', 'user', 'product.category'])
                              ->withCount('comments')
                              ->active()
                              ->latest()
                              ->paginate(12);

        return view('videos.index', compact('videos'));
    }

    // Get single video with details
    public function show(ProductVideo $video)
    {
        $video->load(['product.seller', 'user', 'comments.user']);
        $video->incrementViews();

        return view('videos.show', compact('video'));
    }

    // Toggle like
    public function like(Request $request, ProductVideo $video)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $liked = $video->toggleLike(auth()->id());

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes' => $video->fresh()->likes,
                'formatted_likes' => $video->fresh()->formatted_likes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Add comment
    public function comment(Request $request, ProductVideo $video)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        try {
            $comment = $video->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comment,
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Upload video page
    public function create()
    {
        // Admin can upload for any product, Penjual only for own products
        if (auth()->user()->isAdmin()) {
            $products = Product::with('seller')->where('is_active', true)->get();
        } else {
            $products = Product::where('user_id', auth()->id())
                              ->where('is_active', true)
                              ->get();
        }

        return view('videos.create', compact('products'));
    }

    // Store video
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'video' => 'required|file|mimes:mp4,mov,avi,quicktime|max:102400', // 100MB in KB
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        // Check product ownership if penjual
        if (auth()->user()->isPenjual()) {
            $product = Product::where('id', $request->product_id)
                              ->where('user_id', auth()->id())
                              ->first();

            if (!$product) {
                return back()->withInput()
                             ->with('error', 'Anda tidak memiliki akses ke produk ini.');
            }
        }

        try {
            // Create directory if not exists
            if (!Storage::disk('public')->exists('videos/products')) {
                Storage::disk('public')->makeDirectory('videos/products');
            }

            // Upload video
            $videoFile = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoPath = $videoFile->storeAs('videos/products', $filename, 'public');

            if (!$videoPath) {
                throw new \Exception('Gagal menyimpan video ke storage.');
            }

            // Create video record
            $video = ProductVideo::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'video_path' => $videoPath,
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => true,
                'views' => 0,
                'likes' => 0,
            ]);

            Log::info('Video uploaded successfully', [
                'video_id' => $video->id,
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ]);

            return redirect()->route('videos.index')
                             ->with('success', 'Video berhasil diupload! Video Anda sekarang dapat dilihat oleh pembeli.');

        } catch (\Exception $e) {
            Log::error('Video upload failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            // Delete uploaded file if exists
            if (isset($videoPath) && Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }

            return back()->withInput()
                         ->with('error', 'Gagal mengupload video: ' . $e->getMessage());
        }
    }

    // Delete video
    public function destroy(ProductVideo $video)
    {
        // Only owner or admin can delete
        if ($video->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus video ini.');
        }

        try {
            // Delete video file
            if (Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            // Delete thumbnail if exists
            if ($video->thumbnail_path && Storage::disk('public')->exists($video->thumbnail_path)) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }

            // Delete from database
            $video->delete();

            return back()->with('success', 'Video berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Video delete failed', [
                'video_id' => $video->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus video: ' . $e->getMessage());
        }
    }
}

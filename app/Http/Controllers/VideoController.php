<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\ProductVideo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Display a listing of videos for current seller
     */
    public function index()
    {
        try {
            $products = Product::where('user_id', auth()->id())
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $videos = ProductVideo::with(['product', 'user'])
                ->withCount('comments')
                ->whereHas('product', function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->latest()
                ->paginate(12);

            $stats = [
                'total' => ProductVideo::whereHas('product', function($query) {
                    $query->where('user_id', auth()->id());
                })->count(),

                'total_views' => ProductVideo::whereHas('product', function($query) {
                    $query->where('user_id', auth()->id());
                })->sum('views'),

                'total_likes' => ProductVideo::whereHas('product', function($query) {
                    $query->where('user_id', auth()->id());
                })->sum('likes'),
            ];

            return view('penjual.videos.index', compact('products', 'videos', 'stats'));

        } catch (\Exception $e) {
            Log::error('Penjual videos index error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data video.');
        }
    }


    /**
     * Show the form for creating a new video
     */
    public function create()
    {
        try {
            // Get only seller's own active products
            $products = Product::where('user_id', auth()->id())
                              ->where('is_active', true)
                              ->orderBy('name')
                              ->get();

            return view('penjual.videos.create', compact('products'));

        } catch (\Exception $e) {
            Log::error('Penjual videos create error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman upload.');
        }
    }

    /**
     * Store a newly created video in storage
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'video' => 'required|file|mimes:mp4,mov,avi,quicktime|max:102400', // 100MB
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        // Verify product ownership
        $product = Product::where('id', $request->product_id)
                          ->where('user_id', auth()->id())
                          ->first();

        if (!$product) {
            return back()->withInput()
                         ->with('error', 'Anda tidak memiliki akses ke produk ini.');
        }

        try {
            // Create directory if not exists
            if (!Storage::disk('public')->exists('videos/products')) {
                Storage::disk('public')->makeDirectory('videos/products');
            }

            // Upload video with unique filename
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

            Log::info('Penjual uploaded video', [
                'video_id' => $video->id,
                'seller_id' => auth()->id(),
                'product_id' => $request->product_id,
            ]);

            return redirect()->route('penjual.videos.index')
                             ->with('success', 'Video berhasil diupload! Video Anda sekarang dapat dilihat oleh pembeli.');

        } catch (\Exception $e) {
            Log::error('Penjual video upload failed', [
                'error' => $e->getMessage(),
                'seller_id' => auth()->id(),
            ]);

            // Delete uploaded file if exists
            if (isset($videoPath) && Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }

            return back()->withInput()
                         ->with('error', 'Gagal mengupload video: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified video
     */
    public function show(ProductVideo $video)
    {
        try {
            // Verify ownership
            if ($video->product->user_id !== auth()->id()) {
                abort(403, 'Anda tidak memiliki akses ke video ini.');
            }

            $video->load(['product', 'user', 'comments.user', 'videoLikes']);

            return view('penjual.videos.show', compact('video'));

        } catch (\Exception $e) {
            Log::error('Penjual video show error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail video.');
        }
    }

    /**
     * Remove the specified video from storage
     */
    public function destroy(ProductVideo $video)
    {
        try {
            // Verify ownership
            if ($video->product->user_id !== auth()->id()) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menghapus video ini.');
            }

            // Delete video file
            if (Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            // Delete thumbnail if exists
            if ($video->thumbnail_path && Storage::disk('public')->exists($video->thumbnail_path)) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }

            // Delete from database (will cascade delete likes & comments)
            $video->delete();

            Log::info('Penjual deleted video', [
                'video_id' => $video->id,
                'seller_id' => auth()->id(),
            ]);

            return redirect()->route('penjual.videos.index')
                             ->with('success', 'Video berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Penjual video delete failed', [
                'video_id' => $video->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus video: ' . $e->getMessage());
        }
    }
}

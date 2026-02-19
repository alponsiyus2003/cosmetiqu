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
    public function index()
    {
        $videos = ProductVideo::with(['product', 'user'])
                              ->withCount('comments')
                              ->whereHas('product', function($q) {
                                  $q->where('user_id', auth()->id());
                              })
                              ->latest()
                              ->paginate(20);

        $stats = [
            'total' => ProductVideo::whereHas('product', function($q) {
                $q->where('user_id', auth()->id());
            })->count(),
            'total_views' => ProductVideo::whereHas('product', function($q) {
                $q->where('user_id', auth()->id());
            })->sum('views'),
            'total_likes' => ProductVideo::whereHas('product', function($q) {
                $q->where('user_id', auth()->id());
            })->sum('likes'),
        ];

        return view('penjual.videos.index', compact('videos', 'stats'));
    }

    public function create()
    {
        $products = Product::where('user_id', auth()->id())
                          ->where('is_active', true)
                          ->orderBy('name')
                          ->get();

        return view('penjual.videos.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'video' => 'required|file|mimes:mp4,mov,avi,quicktime|max:102400',
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
            if (!Storage::disk('public')->exists('videos/products')) {
                Storage::disk('public')->makeDirectory('videos/products');
            }

            $videoFile = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoPath = $videoFile->storeAs('videos/products', $filename, 'public');

            if (!$videoPath) {
                throw new \Exception('Gagal menyimpan video ke storage.');
            }

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
            ]);

            return redirect()->route('penjual.videos.index')
                             ->with('success', 'Video berhasil diupload!');

        } catch (\Exception $e) {
            Log::error('Penjual video upload failed', ['error' => $e->getMessage()]);

            if (isset($videoPath) && Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }

            return back()->withInput()
                         ->with('error', 'Gagal mengupload video: ' . $e->getMessage());
        }
    }

    public function show(ProductVideo $video)
    {
        // Verify ownership
        if ($video->product->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke video ini.');
        }

        $video->load(['product', 'user', 'comments.user', 'videoLikes']);
        return view('penjual.videos.show', compact('video'));
    }

    public function destroy(ProductVideo $video)
    {
        // Verify ownership
        if ($video->product->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus video ini.');
        }

        try {
            if (Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            if ($video->thumbnail_path && Storage::disk('public')->exists($video->thumbnail_path)) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }

            $video->delete();

            return redirect()->route('penjual.videos.index')
                             ->with('success', 'Video berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus video: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVideo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Display a listing of all videos
     */
    public function index()
    {
        try {
            $videos = ProductVideo::with(['product', 'user'])
                                  ->withCount('comments')
                                  ->latest()
                                  ->paginate(20);

            $stats = [
                'total' => ProductVideo::count(),
                'active' => ProductVideo::where('is_active', true)->count(),
                'inactive' => ProductVideo::where('is_active', false)->count(),
                'total_views' => ProductVideo::sum('views'),
            ];

            return view('admin.videos.index', compact('videos', 'stats'));

        } catch (\Exception $e) {
            Log::error('Admin videos index error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data video.');
        }
    }

    /**
     * Show the form for creating a new video
     */
    public function create()
    {
        try {
            // Admin can upload for any product
            $products = Product::where('is_active', true)
                              ->with('seller')
                              ->orderBy('name')
                              ->get();

            return view('admin.videos.create', compact('products'));

        } catch (\Exception $e) {
            Log::error('Admin videos create error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman upload.');
        }
    }

    /**
     * Store a newly created video in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'video' => 'required|file|mimes:mp4,mov,avi,quicktime|max:102400',
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            // Create directory if not exists
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

            Log::info('Admin uploaded video', [
                'video_id' => $video->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()->route('admin.videos.index')
                             ->with('success', 'Video berhasil diupload!');

        } catch (\Exception $e) {
            Log::error('Admin video upload failed', ['error' => $e->getMessage()]);

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
            $video->load(['product', 'user', 'comments.user', 'videoLikes']);
            return view('admin.videos.show', compact('video'));
        } catch (\Exception $e) {
            Log::error('Admin video show error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail video.');
        }
    }

    /**
     * Toggle video active status
     */
    public function toggleStatus(ProductVideo $video)
    {
        try {
            $video->update(['is_active' => !$video->is_active]);

            $status = $video->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Video berhasil {$status}!");

        } catch (\Exception $e) {
            Log::error('Admin toggle video status failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengubah status video.');
        }
    }

    /**
     * Remove the specified video from storage
     */
    public function destroy(ProductVideo $video)
    {
        try {
            if (Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            if ($video->thumbnail_path && Storage::disk('public')->exists($video->thumbnail_path)) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }

            $video->delete();

            Log::info('Admin deleted video', [
                'video_id' => $video->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()->route('admin.videos.index')
                             ->with('success', 'Video berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Admin video delete failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus video: ' . $e->getMessage());
        }
    }
}

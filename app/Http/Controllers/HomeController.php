<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Carousel;
use App\Models\ProductVideo;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::active()->get();

        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])->where('is_active', true)->get();

        $featuredProducts = Product::with(['category', 'seller'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $shortVideos = ProductVideo::with(['product', 'user'])
            ->active()
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('carousels', 'categories', 'featuredProducts', 'shortVideos'));
    }
}

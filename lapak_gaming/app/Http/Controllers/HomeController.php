<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()->parent()->ordered()
            ->with('children')
            ->take(13)
            ->get();

        $popularProducts = Product::active()->inStock()->popular()
            ->with(['statistics', 'category', 'seller'])
            ->take(12)
            ->get();

        $topupProducts = Product::active()->inStock()->ofType('topup')
            ->with(['statistics', 'category', 'seller'])
            ->take(8)
            ->get();

        $gameKeyProducts = Product::active()->inStock()->ofType('gamekey')
            ->with(['statistics', 'category', 'seller'])
            ->take(8)
            ->get();

        // Hero Banner
        $heroBanners = Banner::where('position', 'hero')
            ->where('is_active', 1)
            ->latest()
            ->get();

        // Featured Banner
        $featuredBanners = Banner::where('position', 'featured')
            ->where('is_active', 1)
            ->latest()
            ->get();

        return view('home', compact(
            'categories',
            'popularProducts',
            'topupProducts',
            'gameKeyProducts',
            'heroBanners',
            'featuredBanners'
        ));
    }
}
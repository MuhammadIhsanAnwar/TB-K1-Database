<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id', null)
            ->where('is_active', true)
            ->with('children')
            ->get();

        $featured = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['seller', 'category'])
            ->limit(8)
            ->get();

        $trending = Product::where('is_active', true)
            ->with(['seller', 'category'])
            ->orderByDesc('view_count')
            ->limit(12)
            ->get();

        $newest = Product::where('is_active', true)
            ->with(['seller', 'category'])
            ->latest()
            ->limit(12)
            ->get();

        return view('home', compact('categories', 'featured', 'trending', 'newest'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::where('is_active', true)
            ->where(function($query) use ($category) {
                $query->where('category_id', $category->id)
                    ->orWhereIn('category_id', $category->children()->pluck('id'));
            })
            ->with(['seller', 'category'])
            ->paginate(12);

        return view('category', compact('category', 'products'));
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['seller.sellerAccount', 'category', 'reviews'])
            ->firstOrFail();

        // Increment view count
        $product->increment('view_count');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['seller', 'category'])
            ->limit(6)
            ->get();

        return view('product.detail', compact('product', 'relatedProducts'));
    }
}

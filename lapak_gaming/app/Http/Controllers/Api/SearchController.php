<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category');
        $minPrice = $request->get('min_price', 0);
        $maxPrice = $request->get('max_price', 999999999);
        $sort = $request->get('sort', 'newest'); // newest, popular, rating, cheapest

        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->whereBetween('price', [$minPrice, $maxPrice]);

        if ($category) {
            $products->where('category_id', $category);
        }

        $products = match($sort) {
            'popular' => $products->orderBy('view_count', 'desc'),
            'rating' => $products->orderByRaw('rating DESC, total_reviews DESC'),
            'cheapest' => $products->orderBy('price', 'asc'),
            default => $products->latest('created_at'),
        };

        return response()->json(
            $products->with(['seller', 'category'])
                ->paginate(12)
        );
    }

    public function suggestions(Request $request)
    {
        $q = $request->get('q', '');

        $suggestions = Product::where('is_active', true)
            ->where('name', 'like', "%{$q}%")
            ->select('id', 'name', 'slug', 'price')
            ->limit(10)
            ->get();

        return response()->json($suggestions);
    }
}

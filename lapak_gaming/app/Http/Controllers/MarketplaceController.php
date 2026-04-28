<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function home(Request $request): View
    {
        $categories = Schema::hasTable('categories')
            ? Category::query()->whereNull('parent_id')->with('children')->orderBy('sort_order')->get()
            : collect();
        $featuredProducts = Schema::hasTable('products')
            ? Product::query()->published()->with(['seller', 'category'])->where('is_featured', true)->latest()->take(8)->get()
            : collect();
        $trendingProducts = Schema::hasTable('products')
            ? Product::query()->published()->trending()->with(['seller', 'category'])->latest()->take(8)->get()
            : collect();
        $latestProducts = Schema::hasTable('products')
            ? Product::query()->published()->with(['seller', 'category'])->latest()->take(12)->get()
            : collect();

        return view('marketplace.home', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'trendingProducts' => $trendingProducts,
            'latestProducts' => $latestProducts,
            'search' => $request->string('q')->toString(),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        if (! Schema::hasTable('products')) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => 0,
                ],
            ]);
        }

        $products = Product::query()
            ->published()
            ->with(['seller', 'category'])
            ->search($request->string('q')->toString())
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->string('category'))))
            ->when($request->filled('sort'), function ($query) use ($request): void {
                match ($request->string('sort')->toString()) {
                    'popular' => $query->orderByDesc('views_count'),
                    'price_low' => $query->orderBy('price'),
                    'price_high' => $query->orderByDesc('price'),
                    default => $query->latest(),
                };
            })
            ->paginate(12);

        return response()->json([
            'data' => $products->through(function (Product $product): array {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => number_format((float) $product->price, 0, ',', '.'),
                    'rating_average' => (float) $product->rating_average,
                    'review_count' => $product->review_count,
                    'category' => $product->category?->name,
                    'seller' => $product->seller?->name,
                ];
            }),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}
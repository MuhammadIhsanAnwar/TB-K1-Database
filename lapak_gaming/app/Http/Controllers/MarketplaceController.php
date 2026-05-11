<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function home(Request $request): View
    {
        $categories = Schema::hasTable('categories')
            ? Category::query()->active()->whereNull('parent_id')->with('children')->orderBy('sort_order')->take(10)->get()
            : collect();

        $popularProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->with(['seller', 'category'])->inRandomOrder()->take(12)->get()
            : collect();

        $topupProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->ofType('topup')->with(['seller', 'category'])->inRandomOrder()->take(8)->get()
            : collect();

        $featuredProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->where('is_featured', true)->with(['seller', 'category'])->inRandomOrder()->take(8)->get()
            : collect();

        $activeUsers = Schema::hasTable('users')
            ? User::whereNotNull('email_verified_at')->where('status', 'active')->count()
            : 0;

        $availableProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->count()
            : 0;

        $verifiedSellers = Schema::hasTable('sellers')
            ? Seller::query()->verified()->count()
            : 0;

        $averageRating = Schema::hasTable('reviews')
            ? (float) Review::query()->where('is_public', true)->avg('rating')
            : 0.0;

        $transactionCount = Schema::hasTable('orders')
            ? Order::query()->completed()->count()
            : 0;

        return view('marketplace.home', [
            'categories' => $categories,
            'popularProducts' => $popularProducts,
            'topupProducts' => $topupProducts,
            'featuredProducts' => $featuredProducts,
            'search' => $request->string('q')->toString(),
            'activeUsers' => $activeUsers,
            'availableProducts' => $availableProducts,
            'verifiedSellers' => $verifiedSellers,
            'averageRating' => $averageRating,
            'transactionCount' => $transactionCount,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        if (!Schema::hasTable('products')) {
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
            ->when($request->filled('category'), fn($query) => $query->whereHas('category', fn($category) => $category->where('slug', $request->string('category'))))
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

    public function trending(Request $request): View
    {
        $trendingProducts = Schema::hasTable('products')
            ? Product::query()
                ->active()
                ->inStock()
                ->with(['seller', 'category'])
                ->orderByDesc('id')
                ->paginate(12)
            : collect();
        
        return view('marketplace.trending', [
            'products' => $trendingProducts,
        ]);
    }
}
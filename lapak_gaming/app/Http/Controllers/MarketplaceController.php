<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Review;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function home(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if ($request->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $categories = Schema::hasTable('categories')
            ? Category::query()->active()->whereNull('parent_id')->with(['children' => fn ($query) => $query->active()->ordered()])->orderBy('sort_order')->get()
            : collect();

        $allCategories = Schema::hasTable('categories')
            ? Category::query()->active()->whereNull('parent_id')->with(['children' => fn ($query) => $query->active()->ordered()])->orderBy('sort_order')->get()
            : collect();

        // Select highly rated products but randomize order each refresh to keep homepage dynamic
        $popularProducts = Schema::hasTable('products')? Product::query()->active()->inStock()->with(['seller', 'category'])->get()->sortByDesc(function ($product) {
            return ($product->rating_average ?? 0) + rand(0, 3);
        })->take(12) : collect();

        $topupProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->ofType('topup')->with(['seller', 'category'])->inRandomOrder()->take(8)->get()
            : collect();

        $featuredProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->where('is_featured', true)->with(['seller', 'category'])->inRandomOrder()->take(8)->get()
            : collect();

        $activeUsers = Schema::hasTable('users')
            ? User::query()
                ->where('status', 'active')
                ->whereNull('deactivated_at')
                ->count()
            : 0;

        $availableProducts = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->count()
            : 0;

        $verifiedSellers = 0;

        if (Schema::hasTable('sellers')) {
            $verifiedSellers = Seller::query()->active()->verified()->count();
        }

        if ($verifiedSellers === 0 && Schema::hasTable('users')) {
            $verifiedSellers = User::query()
                ->where('role', 'seller')
                ->where('status', 'active')
                ->count();
        }

        $averageRating = Schema::hasTable('reviews')
            ? (float) (Review::query()->avg('rating') ?? 0.0)
            : 0.0;

        $transactionCount = Schema::hasTable('orders')
            ? Order::query()->completed()->count()
            : 0;

        $todayTransactions = Schema::hasTable('orders')
            ? Order::query()->completed()->whereDate('completed_at', now())->count()
            : 0;

        $heroBanners = Schema::hasTable('banners')
            ? Banner::query()->active()->where('position', 'hero')->orderByDesc('sort_order')->latest()->take(6)->get()
            : collect();

        $featuredBanners = Schema::hasTable('banners')
            ? Banner::query()->active()->where('position', 'featured')->latest()->take(6)->get()
            : collect();

        $categoryProducts = collect();

        if (Schema::hasTable('products')) {
            $categoryProducts = $allCategories->map(function (Category $category) {
                return [
                    'category' => $category,
                    'products' => Product::query()
                        ->active()
                        ->inStock()
                        ->where('category_id', $category->id)
                        ->with(['seller', 'category'])
                        ->get()
                    ->sortByDesc(function ($product) {
                        return
                            ($product->rating_average ?? 0) * 2 +
                            ($product->views_count ?? 0) / 100 +
                            rand(0, 5);
                    })
                    ->take(12),
                ];
            })->filter(fn (array $entry) => $entry['products']->isNotEmpty())->values();
        }
        
        return view('marketplace.home', [
            'categories' => $categories,
            'allCategories' => $allCategories,
            'popularProducts' => $popularProducts,
            'topupProducts' => $topupProducts,
            'featuredProducts' => $featuredProducts,
            'search' => $request->string('q')->toString(),
            'activeUsers' => $activeUsers,
            'availableProducts' => $availableProducts,
            'verifiedSellers' => $verifiedSellers,
            'averageRating' => $averageRating,
            'transactionCount' => $transactionCount,
            'todayTransactions' => $todayTransactions,
            'heroBanners' => $heroBanners,
            'featuredBanners' => $featuredBanners,
            'categoryProducts' => $categoryProducts,
        ]);
    }

    public function categories(): View
    {
        $categories = Schema::hasTable('categories')
            ? Category::query()->active()->whereNull('parent_id')->with(['children' => fn ($query) => $query->active()->ordered()])->orderBy('sort_order')->get()
            : collect();

        return view('marketplace.categories', [
            'categories' => $categories,
        ]);
    }

    public function browse(Request $request): View
    {
        $products = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->with(['category', 'seller'])->paginate(20)
            : collect();

        return view('products.search', [
            'products' => $products,
            'query' => $request->string('q')->toString(),
            'title' => 'Semua Produk — Lapak Geming',
            'heading' => 'Semua Produk',
            'description' => 'Jelajahi semua produk aktif di marketplace.',
        ]);
    }

    public function deals(Request $request): View
    {
        $products = Schema::hasTable('products')
            ? Product::query()
                ->active()
                ->inStock()
                ->where(function ($query) {
                    $query->where('is_featured', true)
                          ->orWhereNotNull('sale_price');
                })
                ->with(['category', 'seller'])
                ->paginate(20)
            : collect();

        return view('products.search', [
            'products' => $products,
            'query' => $request->string('q')->toString(),
            'title' => 'Penawaran & Deals — Lapak Geming',
            'heading' => 'Penawaran & Deals',
            'description' => 'Temukan produk unggulan, promo, dan harga diskon terbaik.',
        ]);
    }

    public function category(string $slug): View
    {
        $category = Schema::hasTable('categories')
            ? Category::query()->where('slug', $slug)->firstOrFail()
            : throw new \Illuminate\Database\Eloquent\ModelNotFoundException();

        $childrenIds = $category->children()->pluck('id')->push($category->id)->all();

        $products = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->whereIn('category_id', $childrenIds)->with(['category', 'seller'])->paginate(20)
            : collect();

        return view('products.category', compact('category', 'products'));
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
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

        $allCategories = collect();
        if (Schema::hasTable('categories')) {
            $query = Category::query()->active()->whereNull('parent_id')->with(['children' => fn ($query) => $query->active()->ordered()]);
            if (Schema::hasColumn('categories', 'sort_order')) {
                $query = $query->orderBy('sort_order');
            }
            $allCategories = $query->get();
        }

        $categoryProductIds = function (Category $category): array {
            $childIds = $category->relationLoaded('children')
                ? $category->children->pluck('id')
                : $category->children()->pluck('id');

            return $childIds->push($category->id)->unique()->values()->all();
        };

        $productsForCategory = function (Category $category, int $limit = 12, bool $random = false) use ($categoryProductIds) {
            if (! Schema::hasTable('products')) {
                return collect();
            }

            $query = Product::query()
                ->active()
                ->inStock()
                ->whereIn('category_id', $categoryProductIds($category))
                ->with(['statistics', 'seller', 'category']);

            $random ? $query->inRandomOrder() : $query->latest();

            return $query->take($limit)->get();
        };

        // Determine which categories should be displayed on homepage (only those that have products)
        $displayCategories = collect();
        if ($allCategories->isNotEmpty() && Schema::hasTable('products')) {
            foreach ($allCategories as $cat) {
                $has = Product::query()
                    ->active()
                    ->inStock()
                    ->whereIn('category_id', $categoryProductIds($cat))
                    ->exists();
                if ($has) {
                    $displayCategories->push($cat);
                }
            }
        }

        // 1. Hero Banners
        $heroBanners = collect();
        if (Schema::hasTable('banners')) {
            $bq = Banner::query()->active()->where('position', 'hero');
            if (Schema::hasColumn('banners', 'sort_order')) {
                $bq = $bq->orderByDesc('sort_order')->latest();
            } else {
                $bq = $bq->latest();
            }
            $heroBanners = $bq->get();

            if ($heroBanners->isEmpty()) {
                $heroBanners = Banner::query()
                    ->active()
                    ->latest()
                    ->get();
            }
        }

        // 2. Featured Game Keys ("Unlock the Simulation")
        $gameKeyCategory = $allCategories->firstWhere('slug', 'game-key');
        if (! $gameKeyCategory && Schema::hasTable('categories')) {
            $gameKeyCategory = Category::query()
                ->active()
                ->where('slug', 'game-key')
                ->with(['children' => fn ($query) => $query->active()->ordered()])
                ->first();
        }

        $featuredGameKeys = $gameKeyCategory
            ? $productsForCategory($gameKeyCategory, 12, true)
            : collect();

        // 3. Featured RPG Keys ("Unlock Epic RPG Worlds")
        $featuredRPGKeys = collect();
        if ($gameKeyCategory && Schema::hasTable('products')) {
            $rpgTerms = ['rpg', 'adventure', 'assassin', 'palworld', 'honkai', 'genshin', 'ark', 'atlas', 'atomic', 'banishers'];

            $featuredRPGKeys = Product::query()
                ->active()
                ->inStock()
                ->whereIn('category_id', $categoryProductIds($gameKeyCategory))
                ->where(function ($query) use ($rpgTerms): void {
                    foreach ($rpgTerms as $term) {
                        $query->orWhere('name', 'like', "%{$term}%")
                            ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery
                                ->where('name', 'like', "%{$term}%")
                                ->orWhere('slug', 'like', "%{$term}%"));
                    }
                })
                ->with(['statistics', 'seller', 'category'])
                ->inRandomOrder()
                ->take(12)
                ->get();

            if ($featuredRPGKeys->isEmpty()) {
                $featuredRPGKeys = $featuredGameKeys->shuffle()->take(12)->values();
            }
        }

        // 4. Featured Category Showcase (2-3 selected categories)
        $featuredCategoryShowcases = collect();
        if (Schema::hasTable('products')) {
            $showcaseCategorySlugs = ['akun', 'voucher', 'top-up-login'];
            foreach ($showcaseCategorySlugs as $slug) {
                $cat = $allCategories->firstWhere('slug', $slug);
                if (! $cat) {
                    $cat = Category::query()
                        ->active()
                        ->where('slug', $slug)
                        ->with(['children' => fn ($query) => $query->active()->ordered()])
                        ->first();
                }

                if ($cat) {
                    $products = $productsForCategory($cat, 8);
                    if (!$products->isEmpty()) {
                        $featuredCategoryShowcases->push([
                            'category' => $cat,
                            'products' => $products
                        ]);
                    }
                }
            }
        }

        // 5. Category Sections
        $categorySections = collect();
        if (Schema::hasTable('products')) {
            foreach ($allCategories as $cat) {
                $products = $productsForCategory($cat, 12);
                if ($products->isEmpty()) {
                    continue;
                }

                $categorySections->push([
                    'category' => $cat,
                    'products' => $products,
                ]);
            }
        }

        // Keep default categories if not found by name
        if ($categorySections->isEmpty() && Schema::hasTable('products')) {
            $categorySections = $allCategories->map(function (Category $category) {
                $childrenIds = $category->children()->pluck('id')->push($category->id)->all();

                return [
                    'category' => $category,
                    'products' => Product::query()->active()->inStock()->whereIn('category_id', $childrenIds)->with(['statistics', 'seller', 'category'])->take(12)->get(),
                ];
            })->filter(fn (array $entry) => $entry['products']->isNotEmpty())->take(8)->values();
        }

        $featuredBanners = collect();
        if (Schema::hasTable('banners')) {
            $fq = Banner::query()->active()->where('position', 'featured');
            if (Schema::hasColumn('banners', 'sort_order')) {
                $fq = $fq->orderByDesc('sort_order')->latest();
            } else {
                $fq = $fq->latest();
            }
            $featuredBanners = $fq->take(6)->get();
        }

        // Fallback homepage products (show when categorySections are empty)
        $homepageProducts = collect();
        if (Schema::hasTable('products')) {
            $homepageProducts = Product::query()->active()->inStock()->with(['statistics', 'seller', 'category'])->inRandomOrder()->take(12)->get();
        }

        $activeAccountCount = Schema::hasTable('users') ? User::active()->count() : 0;
        $activeProductCount = Schema::hasTable('products') ? Product::query()->active()->inStock()->count() : 0;
        $verifiedSellerCount = Schema::hasTable('users') ? User::approvedSellers()->count() : 0;
        $transactionCount = Schema::hasTable('orders') ? Order::query()->count() : 0;

        return view('marketplace.home', [
            'allCategories' => $allCategories,
            'displayCategories' => $displayCategories,
            'activeAccountCount' => $activeAccountCount,
            'activeProductCount' => $activeProductCount,
            'verifiedSellerCount' => $verifiedSellerCount,
            'transactionCount' => $transactionCount,
            'heroBanners' => $heroBanners,
            'featuredBanners' => $featuredBanners,
            'featuredGameKeys' => $featuredGameKeys,
            'featuredRPGKeys' => $featuredRPGKeys,
            'featuredCategoryShowcases' => $featuredCategoryShowcases,
            'categorySections' => $categorySections,
            'homepageProducts' => $homepageProducts,
        ]);
    }

    public function categories(): View
    {
        if (Schema::hasTable('categories')) {
            $catQuery = Category::query()->active()->whereNull('parent_id')->with(['children' => fn ($query) => $query->active()->ordered()]);
            if (Schema::hasColumn('categories', 'sort_order')) {
                $catQuery = $catQuery->orderBy('sort_order');
            }
            $categories = $catQuery->get();
        } else {
            $categories = collect();
        }

        return view('marketplace.categories', [
            'categories' => $categories,
        ]);
    }

    public function store(User $seller): View
    {
        abort_unless($seller->isSellerAccount(), 404);

        $seller->loadCount('products');
        $seller->loadMissing(['profile']);

        $products = Schema::hasTable('products')
            ? Product::query()
                ->active()
                ->inStock()
                ->where('seller_id', $seller->id)
                ->with(['statistics', 'category', 'seller'])
                ->latest()
                ->paginate(12)
            : collect();

        return view('marketplace.store', [
            'seller' => $seller,
            'products' => $products,
        ]);
    }

    public function browse(Request $request): View
    {
        $products = Schema::hasTable('products')
            ? Product::query()->active()->inStock()->with(['statistics', 'category', 'seller'])->paginate(20)
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
                ->with(['statistics', 'category', 'seller'])
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
            ? Product::query()->active()->inStock()->whereIn('category_id', $childrenIds)->with(['statistics', 'category', 'seller'])->paginate(20)
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
            ->with(['statistics', 'seller', 'category'])
            ->search($request->string('q')->toString())
            ->when($request->filled('category'), fn($query) => $query->whereHas('category', fn($category) => $category->where('slug', $request->string('category'))))
            ->when($request->filled('sort'), function ($query) use ($request): void {
                match ($request->string('sort')->toString()) {
                    'popular' => $query->mostViewed(),
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
        if (! Schema::hasTable('products')) {
            $trendingProducts = collect();
        } else {
            // Prefer ranking by sold_count, then review_count, then rating_average
            if (Schema::hasTable('product_statistics')) {
                $trendingProducts = Product::query()
                    ->select('products.*')
                    ->leftJoin('product_statistics', 'products.id', '=', 'product_statistics.product_id')
                    ->active()
                    ->inStock()
                    ->where(function ($q) {
                        $q->where('product_statistics.sold_count', '>', 0)
                          ->orWhere('product_statistics.review_count', '>', 0);
                    })
                    ->with(['statistics', 'seller', 'category'])
                    ->orderByDesc('product_statistics.sold_count')
                    ->orderByDesc('product_statistics.review_count')
                    ->orderByDesc('product_statistics.rating_average')
                    ->orderByDesc('products.updated_at')
                    ->paginate(12);
            } else {
                // Fallback: most recently added products
                $trendingProducts = Product::query()
                    ->active()
                    ->inStock()
                    ->with(['statistics', 'seller', 'category'])
                    ->orderByDesc('id')
                    ->paginate(12);
            }
        }

        return view('marketplace.trending', [
            'products' => $trendingProducts,
        ]);
    }
}

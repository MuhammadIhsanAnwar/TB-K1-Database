<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->with([
            'seller' => fn ($query) => $query->withCount('products')->with('profile'),
            'category',
            'statistics',
            'reviews.user',
        ])->firstOrFail();

        $relatedProducts = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['statistics', 'category', 'seller'])
            ->take(6)->get();

        $userOrder = null;
        if (Auth::check()) {
            $userOrder = \App\Models\Order::where('buyer_id', auth()->id())
                ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                ->latest()
                ->first();
        }

        return view('marketplace.product', compact('product', 'relatedProducts', 'userOrder'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        // Removed type filter — only category and price range are supported
        $sort = $request->input('sort', 'popular');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $categorySlug = $request->input('category');
        $perPage = (int) $request->input('per_page', 50);

        if (!in_array($perPage, [50,100,300,500,1000])) {
            $perPage = 50;
        }

        $products = Product::active()->inStock()
            ->when($query, fn($q) => $q->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")->orWhere('description', 'like', "%$query%");
            }))
            ->when($categorySlug, function($q) use ($categorySlug) {
                // support both slug and numeric id, include direct children categories
                if (ctype_digit((string) $categorySlug)) {
                    $id = (int) $categorySlug;
                    $q->where(function($sub) use ($id) {
                        $sub->where('category_id', $id)->orWhereHas('category', fn($c) => $c->where('parent_id', $id));
                    });
                } else {
                    $cat = \App\Models\Category::where('slug', $categorySlug)->first();
                    if ($cat) {
                        $children = $cat->children()->pluck('id')->push($cat->id)->all();
                        $q->whereIn('category_id', $children);
                    } else {
                        // fallback to matching slug on related category record
                        $q->whereHas('category', fn($c) => $c->where('slug', $categorySlug));
                    }
                }
            })
            ->when($minPrice, fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn($q) => $q->where('price', '<=', $maxPrice))
            ->when($sort === 'popular', fn($q) => $q->popular())
            ->when($sort === 'rating', fn($q) => $q->topRated())
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->with(['statistics', 'category', 'seller'])
            ->paginate($perPage)->withQueryString();

        $categories = collect();
        if (class_exists(Category::class)) {
            $categories = Category::query()
                ->active()
                ->whereNull('parent_id')
                ->ordered()
                ->take(13)
                ->get();
        }

        return view('products.search', compact('products', 'query', 'categories'));
    }

    public function byType(string $type)
    {
        $perPage = (int) request()->input('per_page', 50);
        if (!in_array($perPage, [50, 100, 300, 500, 1000])) {
            $perPage = 50;
        }

        $products = Product::query()
            ->active()
            ->where(function ($query) use ($type) {
                $query->where('type', 'LIKE', "%{$type}%")
                      ->orWhere('name', 'LIKE', "%{$type}%")
                      ->orWhereHas('category', function ($q) use ($type) {
                          $q->where('slug', 'LIKE', "%{$type}%")
                            ->orWhere('name', 'LIKE', "%{$type}%");
                      });
            })
            ->with(['statistics', 'category', 'seller'])
            ->paginate($perPage)->withQueryString();

        return view('products.by-type', compact('products', 'type'));
    }

    public function byCategory(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $childrenIds = $category->children()->pluck('id')->push($category->id)->all();

        $type = $request->input('type');
        $sort = $request->input('sort', 'popular');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $perPage = (int) $request->input('per_page', 50);
        if (!in_array($perPage, [50, 100, 300, 500, 1000])) {
            $perPage = 50;
        }

        $products = Product::active()->inStock()
            ->whereIn('category_id', $childrenIds)
            ->when($type, fn($q) => $q->where('type', $type))
            ->when($minPrice, fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn($q) => $q->where('price', '<=', $maxPrice))
            ->when($sort === 'popular', fn($q) => $q->popular())
            ->when($sort === 'rating', fn($q) => $q->topRated())
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->with(['statistics', 'category', 'seller'])
            ->paginate($perPage)->withQueryString();

        return view('products.category', compact('category', 'products'));
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStatistic;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function show(string $slug) {
        $product = Product::where('slug', $slug)->with([
            'seller', 'category', 'reviews.user',
        ])->firstOrFail();

        $relatedProducts = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(6)->get();

        return view('marketplace.product', compact('product', 'relatedProducts'));
    }

    public function search(Request $request) {
        $query = $request->input('q', '');
        $type  = $request->input('type');
        $sort  = $request->input('sort', 'popular');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $products = Product::active()->inStock()
            ->when($query, fn($q) => $q->where('name', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%"))
            ->when($type, fn($q) => $q->where('type', $type))
            ->when($minPrice, fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn($q) => $q->where('price', '<=', $maxPrice))
            ->when($sort === 'popular',  fn($q) => $q->popular())
            ->when($sort === 'rating',   fn($q) => $q->topRated())
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->with(['category', 'seller'])
            ->paginate(20)->withQueryString();

        return view('products.search', compact('products', 'query'));
    }

    public function byType(string $type) {
        $products = Product::active()->inStock()->ofType($type)
            ->with(['category', 'seller'])
            ->paginate(20);

        return view('products.by-type', compact('products', 'type'));
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $childrenIds = $category->children()->pluck('id')->push($category->id)->all();

        $products = Product::active()->inStock()
            ->whereIn('category_id', $childrenIds)
            ->with(['category', 'seller'])
            ->paginate(20);

        return view('products.category', compact('category', 'products'));
    }
}

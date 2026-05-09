<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStatistic;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->with([
            'seller',
            'category',
            'reviews.user',
        ])->firstOrFail();

        $relatedProducts = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(6)->get();

        // Cek apakah user yang login punya order untuk produk ini
        $userOrder = null;
        if (auth()->check()) {
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
        $type = $request->input('type');
        $sort = $request->input('sort', 'popular');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $products = Product::active()->inStock()
            ->when($query, fn($q) => $q->where('name', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%"))
            ->when($type, fn($q) => $q->where('type', $type))
            ->when($minPrice, fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn($q) => $q->where('price', '<=', $maxPrice))
            ->when($sort === 'popular', fn($q) => $q->popular())
            ->when($sort === 'rating', fn($q) => $q->topRated())
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->with(['category', 'seller'])
            ->paginate(20)->withQueryString();

        return view('products.search', compact('products', 'query'));
    }

public function byType(string $type)
    {
        $products = Product::query()
            ->active()
            // ->inStock() // PASTIKAN BARIS INI DIHAPUS / DI-COMMENT. Jasa Joki biasanya stoknya 0.
            ->where(function ($query) use ($type) {
                // 1. Cari berdasarkan kolom type (Pencarian utama)
                $query->where('type', 'LIKE', "%{$type}%")
                      // 2. Backup: Kalau kolom type kosong/salah, cari dari Nama Produk
                      ->orWhere('name', 'LIKE', "%{$type}%")
                      // 3. Backup: Cari dari Nama Kategorinya
                      ->orWhereHas('category', function ($q) use ($type) {
                          $q->where('slug', 'LIKE', "%{$type}%")
                            ->orWhere('name', 'LIKE', "%{$type}%");
                      });
            })
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

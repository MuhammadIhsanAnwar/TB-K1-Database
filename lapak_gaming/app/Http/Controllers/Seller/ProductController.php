<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SellerAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        /** @var User $seller */
        $seller = Auth::user();
        $products = $seller->products()
            ->with('category')
            ->paginate(15);

        return view('seller.product.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('parent_id', '!=', null)->get();
        return view('seller.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'requirements' => 'nullable|string',
            'delivery_method' => 'required|string|max:100',
        ]);

        $validated['seller_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name'] . ' ' . time());

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return abort(403);
        }

        $categories = \App\Models\Category::where('parent_id', '!=', null)->get();
        return view('seller.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'requirements' => 'nullable|string',
            'delivery_method' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function delete(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return abort(403);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}

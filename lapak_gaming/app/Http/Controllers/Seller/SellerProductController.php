<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerProductController extends Controller {
    
    public function dashboard() {
        $seller = Auth::user();
        
        // Ambil data produk untuk dihitung di Blade
        $products = Product::where('seller_id', $seller->id)->get();
        
        // Ambil data order terkait seller ini (via OrderItem)
        $orders = OrderItem::where('seller_id', $seller->id)
            ->with(['order', 'product'])
            ->latest()
            ->get();

        return view('dashboard.seller', compact('seller', 'products', 'orders'));
    }

    public function index() {
        $products = Product::where('seller_id', Auth::id())
            ->with(['category'])
            ->latest()
            ->paginate(15);
        return view('seller.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:100',
            'stock'       => 'required|integer|min:0',
            'type'        => 'required|in:topup,item,akun,voucher,gamekey',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['seller_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['status'] = 'published';

        Product::create($validated);

        return redirect()->route('seller.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $produk) {
        abort_if($produk->seller_id !== Auth::id(), 403);
        $categories = Category::all();
        return view('seller.products.edit', compact('produk', 'categories'));
    }

    public function update(Request $request, Product $produk) {
        abort_if($produk->seller_id !== Auth::id(), 403);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:100',
            'stock'       => 'required|integer|min:0',
            'type'        => 'required|in:topup,item,akun,voucher,gamekey',
            'status'      => 'required|in:draft,published,archived',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($produk->image) Storage::disk('public')->delete($produk->image);
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $produk->update($validated);

        return redirect()->route('seller.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $produk) {
        abort_if($produk->seller_id !== Auth::id(), 403);
        // Mengubah status jadi archived (Soft Delete manual)
        $produk->update(['status' => 'archived']);
        return back()->with('success', 'Produk berhasil diarsipkan.');
    }
}
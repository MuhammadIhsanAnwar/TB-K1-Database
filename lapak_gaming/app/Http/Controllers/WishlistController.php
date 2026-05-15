<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        return redirect()->route('cart.index');
    }

    public function add($productId)
    {
        $product = Product::findOrFail($productId);

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $product->id],
            ['quantity' => 1]
        );

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function remove($productId)
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
    public function index() {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.seller')
            ->get();
        $total = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => \DB::raw('quantity + ' . (int)$request->quantity)]
        );

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, int $id) {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);
        Cart::where('id', $id)->where('user_id', Auth::id())
            ->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(int $id) {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear() {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan.');
    }
}
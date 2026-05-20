<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller {
    public function index() {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('carts', 'notes') || !\Illuminate\Support\Facades\Schema::hasColumn('carts', 'is_selected')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $e) {
                // Silently skip if migration fails
            }
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.seller')
            ->get();
        
        $selectedItems = $cartItems->filter(fn($c) => $c->is_selected);
        $total = $selectedItems->sum(fn($c) => $c->product->price * $c->quantity);
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request) {
        $messages = [
            'product_id.required' => 'ID produk wajib disertakan.',
            'product_id.exists' => 'Produk tidak ditemukan atau sudah dihapus.',
            'quantity.required' => 'Jumlah produk wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah produk minimal 1.',
            'quantity.max' => 'Jumlah produk maksimal 99.',
        ];

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ], $messages);

        $product = Product::findOrFail($request->product_id);
        $cart = Cart::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        $currentQuantity = (int) ($cart->exists ? $cart->quantity : 0);
        $requestedQuantity = (int) $request->quantity;

        if (($currentQuantity + $requestedQuantity) > (int) $product->stock) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        $cart->quantity = $currentQuantity + $requestedQuantity;
        $cart->save();

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, int $id) {
        $messages = [
            'quantity.required' => 'Jumlah produk wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah produk minimal 1.',
            'quantity.max' => 'Jumlah produk maksimal 99.',
        ];

        $request->validate(['quantity' => 'required|integer|min:1|max:99'], $messages);
        
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($request->quantity > $cart->product->stock) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak cukup! Maksimal stok: ' . $cart->product->stock,
                ], 422);
            }
            return back()->with('error', 'Stok tidak cukup!');
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'quantity' => $cart->quantity,
            ]);
        }
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function toggleSelect(Request $request, $id) {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->is_selected = !$cart->is_selected;
        $cart->save();

        return response()->json([
            'success' => true,
            'is_selected' => $cart->is_selected,
        ]);
    }

    public function updateNote(Request $request, $id) {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->notes = $request->notes;
        $cart->save();

        return response()->json([
            'success' => true,
        ]);
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
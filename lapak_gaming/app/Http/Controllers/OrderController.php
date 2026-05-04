<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class OrderController extends Controller {
    public function index() {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order) {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product.seller', 'items.review');
        return view('orders.show', compact('order'));
    }

    public function checkout(Request $request) {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Validasi stok
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->product->name} tidak mencukupi!");
            }
        }

        $subtotal = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
        $fee = round($subtotal * 0.02); // 2% platform fee
        $total = $subtotal + $fee;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'fee', 'total'));
    }

    public function pay(Request $request, Order $order) {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'pending', 422, 'Order sudah diproses.');

        $request->validate([
            'payment_method' => 'required|in:balance,transfer,qris,dana,ovo,gopay',
            'payment_proof'  => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $order) {
            if ($request->payment_method === 'balance') {
                $user = Auth::user();
                if ($user->balance < $order->total_price) {
                    throw new \Exception('Saldo tidak mencukupi!');
                }
                $user->deductBalance($order->total_price, "Pembayaran Order #{$order->order_code}", $order->id);
                $order->update(['status' => 'paid', 'payment_method' => 'balance', 'paid_at' => now()]);
            } else {
                $proof = null;
                if ($request->hasFile('payment_proof')) {
                    $proof = $request->file('payment_proof')->store('payment_proofs', 'public');
                }
                $order->update([
                    'payment_method' => $request->payment_method,
                    'payment_proof'  => $proof,
                    'status'         => 'paid',
                    'paid_at'        => now(),
                ]);
            }
        });

        return redirect()->route('orders.show', $order->order_code)
            ->with('success', 'Pembayaran berhasil!');
    }

    public function store(Request $request) {
        $request->validate(['payment_method' => 'required|string']);

        DB::transaction(function () use ($request) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            $subtotal = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
            $fee = round($subtotal * 0.02);

            $order = Order::create([
                'user_id'        => Auth::id(),
                'subtotal'       => $subtotal,
                'fee'            => $fee,
                'total_price'    => $subtotal + $fee,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'seller_id'     => $item->product->user_id,
                    'product_name'  => $item->product->name,
                    'price'         => $item->product->price,
                    'quantity'      => $item->quantity,
                    'subtotal'      => $item->product->price * $item->quantity,
                ]);
                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan cart
            Cart::where('user_id', Auth::id())->delete();

            session(['last_order_code' => $order->order_code]);
        });

        return redirect()->route('orders.show', session('last_order_code'))
            ->with('success', 'Order berhasil dibuat!');
    }

    public function complete(Order $order) {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if(!in_array($order->status, ['processing', 'paid']), 422);

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'completed', 'completed_at' => now()]);

            // Kredit saldo ke seller
            foreach ($order->items as $item) {
                $sellerAmount = $item->subtotal * 0.95; // 95% ke seller, 5% platform
                $item->seller->addBalance($sellerAmount, "Penjualan Order #{$order->order_code}", $order->id);
                $item->update(['delivery_status' => 'received']);
                $item->product->increment('sold_count', $item->quantity);
            }
        });

        return redirect()->route('orders.show', $order->order_code)
            ->with('success', 'Order diselesaikan! Saldo seller telah diperbarui.');
    }

    public function cancel(Order $order) {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if(!in_array($order->status, ['pending', 'paid']), 422);

        DB::transaction(function () use ($order) {
            // Kembalikan stok
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            // Refund jika sudah bayar pakai saldo
            if ($order->status === 'paid' && $order->payment_method === 'balance') {
                $order->buyer->addBalance($order->total_price, "Refund Order #{$order->order_code}", $order->id);
            }
            $order->update(['status' => 'cancelled']);
        });

        return redirect()->route('orders.index')->with('success', 'Order dibatalkan.');
    }

    public function uploadProof(Request $request, Order $order) {
        abort_if($order->user_id !== Auth::id(), 403);
        $request->validate(['payment_proof' => 'required|image|max:2048']);
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $order->update(['payment_proof' => $path, 'status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }
}
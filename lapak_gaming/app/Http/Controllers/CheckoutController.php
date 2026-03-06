<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout($productSlug)
    {
        $product = Product::where('slug', $productSlug)
            ->where('is_active', true)
            ->firstOrFail();

        if ($product->stock <= 0) {
            return back()->with('error', 'Produk sudah habis');
        }

        /** @var User $user */
        $user = Auth::user();

        // Check wallet balance
        $wallet = $user->wallet;
        $totalPrice = $product->price;

        return view('checkout.confirm', compact('product', 'wallet', 'totalPrice'));
    }

    public function process(Request $request, $productSlug)
    {
        /** @var User $user */
        $user = Auth::user();
        $product = Product::where('slug', $productSlug)->firstOrFail();

        $validated = $request->validate([
            'payment_method' => 'required|in:wallet,bank_transfer',
            'buyer_note' => 'nullable|string|max:500',
        ]);

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . date('YmdHis') . '-' . Str::random(4),
            'buyer_id' => $user->id,
            'seller_id' => $product->seller_id,
            'status' => $validated['payment_method'] === 'wallet' ? 'processing' : 'pending_payment',
            'subtotal' => $product->price,
            'fee' => $this->calculateFee($product),
            'total' => $product->price + $this->calculateFee($product),
            'buyer_note' => $validated['buyer_note'] ?? null,
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);

        // Process payment
        if ($validated['payment_method'] === 'wallet') {
            return $this->processWalletPayment($order, $user);
        } else {
            return redirect()->route('order.show', $order->id)
                ->with('info', 'Silakan upload bukti pembayaran bank');
        }
    }

    private function processWalletPayment(Order $order, $user)
    {
        $wallet = $user->wallet;

        // Check balance
        if ($wallet->balance < $order->total) {
            $order->delete();
            return back()->with('error', 'Saldo wallet tidak cukup');
        }

        // Deduct from buyer wallet
        $wallet->decrement('balance', $order->total);
        $wallet->increment('hold_balance', $order->total);

        // Create wallet transaction for buyer
        $wallet->transactions()->create([
            'type' => 'order_payment',
            'amount' => $order->total,
            'balance_before' => $wallet->balance + $order->total,
            'balance_after' => $wallet->balance,
            'description' => 'Pembayaran order #' . $order->order_number,
            'reference_type' => 'order',
            'reference_id' => $order->id,
        ]);

        // Update order status
        $order->update([
            'status' => 'processing',
            'payment_uploaded_at' => now(),
        ]);

        // Notify seller
        Notification::create([
            'user_id' => $order->seller_id,
            'type' => 'order_status',
            'title' => 'Pesanan Baru!',
            'message' => 'Ada pesanan baru dari ' . $user->name,
            'action_url' => route('order.show', $order->id),
        ]);

        return redirect()->route('order.show', $order->id)
            ->with('success', 'Pembayaran berhasil! Seller akan segera memproses pesanan Anda.');
    }

    private function calculateFee(Product $product)
    {
        $commissionRate = $product->seller->sellerAccount->level->commission_rate ?? 15;
        return ($product->price * $commissionRate) / 100;
    }
}

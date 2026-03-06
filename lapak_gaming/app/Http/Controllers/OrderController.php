<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        // Verify user is buyer or seller or admin
        /** @var User|null $user */
        $user = Auth::user();
        
        if ($user && !$user->isAdmin() && Auth::id() !== $order->buyer_id && Auth::id() !== $order->seller_id) {
            return abort(403);
        }

        return view('order.detail', compact('order'));
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            return abort(403);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'payment_uploaded_at' => now(),
            'status' => 'payment_uploaded',
        ]);

        // Notify seller
        Notification::create([
            'user_id' => $order->seller_id,
            'type' => 'order_status',
            'title' => 'Bukti Pembayaran Diterima',
            'message' => 'Pembayaran order #' . $order->order_number . ' telah diunggah',
            'action_url' => route('order.show', $order->id),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah');
    }

    public function confirmDelivery(Request $request, Order $order)
    {
        if (Auth::id() !== $order->seller_id) {
            return abort(403);
        }

        if ($order->status !== 'payment_uploaded' && $order->status !== 'processing') {
            return back()->with('error', 'Status order tidak sesuai');
        }

        // Update order status
        $order->update(['status' => 'delivered']);

        // Mark items as delivered
        $order->items()->update([
            'delivered_at' => now(),
        ]);

        // Notify buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'type' => 'order_status',
            'title' => 'Produk Terkirim!',
            'message' => 'Produk order #' . $order->order_number . ' telah dikirim. Silakan verifikasi dalam 24 jam.',
            'action_url' => route('order.show', $order->id),
        ]);

        return back()->with('success', 'Status diubah menjadi terkirim');
    }

    public function confirmReceipt(Request $request, Order $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            return abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Pesanan belum dikirim');
        }

        $order->update([
            'status' => 'completed',
            'confirmed_at' => now(),
        ]);

        $order->items()->update([
            'is_buyer_confirmed' => true,
            'buyer_confirmed_at' => now(),
        ]);

        // Transfer funds to seller wallet
        $this->transferToSellerWallet($order);

        // Notify seller
        Notification::create([
            'user_id' => $order->seller_id,
            'type' => 'order_status',
            'title' => 'Order Selesai & Dana Masuk!',
            'message' => 'Pembeli telah mengkonfirmasi pesanan. Dana masuk ke akun Anda!',
            'action_url' => route('order.show', $order->id),
        ]);

        return back()->with('success', 'Terima kasih! Pesanan selesai. Silakan berikan rating untuk seller (opsional).');
    }

    public function dispute(Request $request, Order $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            return abort(403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $order->update([
            'is_dispute' => true,
            'dispute_reason' => $validated['reason'],
            'status' => 'disputed',
            'disputed_at' => now(),
        ]);

        // Notify admin
        Notification::create([
            'user_id' => 1, // Admin user
            'type' => 'system',
            'title' => 'Dispute Order Baru',
            'message' => 'Order #' . $order->order_number . ' di-dispute oleh pembeli',
            'action_url' => route('admin.order', $order->id),
        ]);

        return back()->with('success', 'Dispute telah dibuat. Admin akan meninjau dalam 24 jam.');
    }

    public function submitReview(Request $request, Order $order)
    {
        if (Auth::id() !== $order->buyer_id || $order->status !== 'completed') {
            return abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Create review for each item
        foreach ($order->items as $item) {
            Review::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'buyer_id' => Auth::id(),
                'seller_id' => $order->seller_id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);
        }

        // Update product and seller ratings
        $this->updateRatings($order);

        return back()->with('success', 'Review berhasil dikirim. Terima kasih!');
    }

    private function transferToSellerWallet(Order $order)
    {
        $seller = $order->seller;
        $wallet = $seller->wallet;

        // Calculate seller income = subtotal - fee
        $sellerIncome = $order->subtotal - $order->fee;

        // Update seller wallet
        $wallet->increment('balance', $sellerIncome);
        $wallet->decrement('hold_balance', 0); // No hold for this simplified version

        // Deduct from buyer's hold
        $buyerWallet = $order->buyer->wallet;
        $buyerWallet->decrement('hold_balance', $order->total);

        // Create wallet transactions
        $wallet->transactions()->create([
            'type' => 'order_payment',
            'amount' => $sellerIncome,
            'balance_before' => $wallet->balance - $sellerIncome,
            'balance_after' => $wallet->balance,
            'description' => 'Penerimaan dari order #' . $order->order_number,
            'reference_type' => 'order',
            'reference_id' => $order->id,
        ]);

        $buyerWallet->transactions()->create([
            'type' => 'order_refund',
            'amount' => -$order->fee,
            'balance_before' => $buyerWallet->balance + $order->fee,
            'balance_after' => $buyerWallet->balance,
            'description' => 'Komisi dari order #' . $order->order_number,
            'reference_type' => 'order',
            'reference_id' => $order->id,
        ]);
    }

    private function updateRatings(Order $order)
    {
        $reviews = $order->reviews()->get();
        
        if ($reviews->isEmpty()) return;

        $avgRating = $reviews->avg('rating');

        // Update product ratings
        foreach ($order->items as $item) {
            $product = $item->product;
            $product->update([
                'rating' => $product->reviews()->avg('rating') ?? 0,
                'total_reviews' => $product->reviews()->count(),
            ]);
        }

        // Update seller ratings
        $seller = $order->seller->sellerAccount;
        $seller->update([
            'rating' => $avgRating,
            'total_reviews' => Review::where('seller_id', $order->seller_id)->count(),
        ]);
    }
}

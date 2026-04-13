<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $product = Product::query()->published()->findOrFail($data['product_id']);
        $quantity = (int) ($data['quantity'] ?? 1);
        $subtotal = $quantity * (float) $product->price;
        $feePercent = 5;
        $feeAmount = round($subtotal * $feePercent / 100, 2);
        $grandTotal = $subtotal + $feeAmount;

        DB::transaction(function () use ($request, $product, $quantity, $subtotal, $feeAmount, $grandTotal, $data): void {
            $order = Order::create([
                'buyer_id' => $request->user()->id,
                'seller_id' => $product->seller_id,
                'invoice_number' => 'INV-'.now()->format('YmdHis').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT),
                'status' => Order::STATUS_PENDING_PAYMENT,
                'subtotal' => $subtotal,
                'fee_amount' => $feeAmount,
                'escrow_amount' => $subtotal,
                'grand_total' => $grandTotal,
                'payment_method' => $data['payment_method'] ?? 'wallet',
                'due_at' => now()->addDays(2),
                'metadata' => ['fee_percent' => 5],
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'seller_id' => $product->seller_id,
                'name_snapshot' => $product->name,
                'price_snapshot' => $product->price,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);

            $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id]);

            if ($wallet->available_balance >= $grandTotal) {
                $wallet->forceFill([
                    'available_balance' => $wallet->available_balance - $grandTotal,
                    'locked_balance' => $wallet->locked_balance + $grandTotal,
                ])->save();

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => $request->user()->id,
                    'type' => 'escrow_hold',
                    'direction' => 'debit',
                    'amount' => $grandTotal,
                    'balance_before' => $wallet->balance,
                    'balance_after' => $wallet->balance,
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                    'description' => 'Dana ditahan di escrow untuk invoice '.$order->invoice_number,
                ]);
            }
        });

        return back()->with('success', 'Checkout berhasil dibuat. Lanjutkan pembayaran untuk masuk ke escrow.');
    }

    public function confirm(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        DB::transaction(function () use ($order): void {
            $order->forceFill([
                'status' => Order::STATUS_COMPLETED,
                'completed_at' => now(),
            ])->save();

            $sellerWallet = Wallet::firstOrCreate(['user_id' => $order->seller_id]);
            $sellerWallet->forceFill([
                'balance' => $sellerWallet->balance + $order->escrow_amount,
                'available_balance' => $sellerWallet->available_balance + $order->escrow_amount,
            ])->save();
        });

        return back()->with('success', 'Pesanan dikonfirmasi dan dana dilepas ke seller.');
    }

    public function dispute(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        $order->forceFill([
            'status' => Order::STATUS_DISPUTED,
            'disputed_at' => now(),
        ])->save();

        return back()->with('success', 'Dispute berhasil diajukan.');
    }

    public function deliver(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->seller_id === $request->user()->id, 403);

        $order->forceFill([
            'status' => Order::STATUS_DELIVERED,
        ])->save();

        return back()->with('success', 'Item digital ditandai sudah dikirim.');
    }
}
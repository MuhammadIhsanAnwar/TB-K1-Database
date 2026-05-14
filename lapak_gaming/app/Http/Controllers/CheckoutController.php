<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderFinancial;
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
        $messages = [
            'product_id.required' => 'ID produk wajib disertakan.',
            'product_id.exists' => 'Produk tidak ditemukan atau sudah dihapus.',
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah produk minimal 1.',
            'quantity.max' => 'Jumlah produk maksimal 99.',
            'payment_method.string' => 'Metode pembayaran harus berupa teks.',
            'payment_method.max' => 'Metode pembayaran maksimal 50 karakter.',
        ];

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ], $messages);

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
                'status' => 'pending_payment',
                'payment_method' => $data['payment_method'] ?? 'wallet',
                'due_at' => now()->addDays(2),
                'metadata' => ['fee_percent' => 5],
            ]);

            $order->financial()->create([
                'subtotal' => $subtotal,
                'fee_amount' => $feeAmount,
                'escrow_amount' => $subtotal,
                'grand_total' => $grandTotal,
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
            $balanceState = $wallet->balanceState()->firstOrCreate([], [
                'balance' => 0,
                'available_balance' => 0,
                'locked_balance' => 0,
            ]);

            if ($balanceState->available_balance >= $grandTotal) {
                $balanceState->forceFill([
                    'available_balance' => (float) $balanceState->available_balance - $grandTotal,
                    'locked_balance' => (float) $balanceState->locked_balance + $grandTotal,
                ])->save();

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => $request->user()->id,
                    'type' => 'escrow_hold',
                    'direction' => 'debit',
                    'amount' => $grandTotal,
                    'balance_before' => $balanceState->balance,
                    'balance_after' => $balanceState->balance,
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
                'status' => 'completed',
                'completed_at' => now(),
            ])->save();

            $sellerWallet = Wallet::firstOrCreate(['user_id' => $order->seller_id]);
            $balanceState = $sellerWallet->balanceState()->firstOrCreate([], [
                'balance' => 0,
                'available_balance' => 0,
                'locked_balance' => 0,
            ]);

            $balanceState->forceFill([
                'balance' => (float) $balanceState->balance + $order->escrow_amount,
                'available_balance' => (float) $balanceState->available_balance + $order->escrow_amount,
            ])->save();
        });

        return back()->with('success', 'Pesanan dikonfirmasi dan dana dilepas ke seller.');
    }

    public function dispute(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        $order->forceFill([
                'status' => 'disputed',
            'disputed_at' => now(),
        ])->save();

        return back()->with('success', 'Dispute berhasil diajukan.');
    }

    public function deliver(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->seller_id === $request->user()->id, 403);

        $order->forceFill([
                'status' => 'delivered',
        ])->save();

        return back()->with('success', 'Item digital ditandai sudah dikirim.');
    }
}
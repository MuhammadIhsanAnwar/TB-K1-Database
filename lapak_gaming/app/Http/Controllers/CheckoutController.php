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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function product(Request $request, Product $product): View|RedirectResponse
    {
        $messages = [
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah produk minimal 1.',
            'quantity.max' => 'Jumlah produk maksimal 99.',
        ];

        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ], $messages);

        $product->load(['seller', 'category']);
        abort_unless($product->status === 'published', 404);
        abort_unless(! empty($product->seller) && ! $product->seller->deactivated_at, 404);

        if ((int) $product->seller_id === (int) $request->user()->id) {
            return redirect()->route('products.show', $product->slug)
                ->with('error', 'Anda tidak bisa checkout produk sendiri.');
        }

        $quantity = (int) ($data['quantity'] ?? 1);

        if ((int) $product->stock < 1) {
            return redirect()->route('products.show', $product->slug)
                ->with('error', 'Stok produk sedang kosong.');
        }

        if ($quantity > (int) $product->stock) {
            return redirect()->route('products.show', $product->slug)
                ->with('error', 'Stok produk tidak mencukupi untuk jumlah yang dipilih.');
        }

        $subtotal = $quantity * (float) $product->price;
        $feePercent = 5;
        $feeAmount = round($subtotal * $feePercent / 100, 2);
        $grandTotal = $subtotal + $feeAmount;
        $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id]);
        $wallet->loadMissing('balanceState');
        $availableBalance = (float) ($wallet->balanceState?->available_balance ?? 0);

        return view('orders.product-checkout', compact(
            'product',
            'quantity',
            'subtotal',
            'feePercent',
            'feeAmount',
            'grandTotal',
            'availableBalance'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'product_id.required' => 'ID produk wajib disertakan.',
            'product_id.exists' => 'Produk tidak ditemukan atau sudah dihapus.',
            'quantity.integer' => 'Jumlah harus berupa angka bulat.',
            'quantity.min' => 'Jumlah produk minimal 1.',
            'quantity.max' => 'Jumlah produk maksimal 99.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ];

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'payment_method' => ['required', 'in:wallet'],
        ], $messages);

        $quantity = (int) ($data['quantity'] ?? 1);
        $paymentMethod = 'wallet';

        $order = DB::transaction(function () use ($request, $quantity, $paymentMethod): Order {
            $product = Product::query()
                ->published()
                ->whereHas('seller', fn ($seller) => $seller->whereNull('deactivated_at'))
                ->lockForUpdate()
                ->findOrFail($request->input('product_id'));

            if ((int) $product->seller_id === (int) $request->user()->id) {
                throw ValidationException::withMessages([
                    'product_id' => 'Anda tidak bisa checkout produk sendiri.',
                ]);
            }

            if ($quantity > (int) $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok produk tidak mencukupi untuk jumlah yang dipilih.',
                ]);
            }

            $subtotal = $quantity * (float) $product->price;
            $feePercent = 5;
            $feeAmount = round($subtotal * $feePercent / 100, 2);
            $grandTotal = $subtotal + $feeAmount;

            $order = Order::create([
                'buyer_id' => $request->user()->id,
                'seller_id' => $product->seller_id,
                'invoice_number' => 'INV-'.now()->format('YmdHis').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT),
                'status' => 'pending_payment',
                'payment_method' => $paymentMethod,
                'due_at' => now()->addDays(2),
                'metadata' => [
                    'fee_percent' => $feePercent,
                    'checkout_source' => 'product',
                ],
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
                'name_snapshot' => $product->name,
                'price_snapshot' => $product->price,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);

            $product->decrement('stock', $quantity);

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

            return $order;
        });

        return redirect()->route('orders.show', $order->order_code)
            ->with('success', 'Pesanan berhasil dibuat. Lanjutkan pembayaran untuk masuk ke escrow.');
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

    public function process(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->seller_id === $request->user()->id, 403);
        abort_unless($order->status === Order::STATUS_PAYMENT_UPLOADED, 422, 'Order harus menunggu proses sebelum dipindahkan ke status diproses.');

        $order->forceFill([
            'status' => Order::STATUS_PROCESSING,
        ])->save();

        return back()->with('success', 'Pesanan berhasil dipindahkan ke status diproses.');
    }

    public function deliver(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->seller_id === $request->user()->id, 403);
        abort_unless($order->status === Order::STATUS_PROCESSING, 422, 'Order harus berstatus diproses sebelum ditandai sudah dikirim.');

        $order->forceFill([
            'status' => Order::STATUS_DELIVERED,
        ])->save();

        return back()->with('success', 'Pesanan berhasil ditandai sudah dikirim.');
    }
}

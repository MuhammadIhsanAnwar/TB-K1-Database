<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\Pdf\PdfDocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller {
    public function index(Request $request) {
        $status = $request->query('status');
        $allowedStatuses = [
            'all',
            Order::STATUS_PENDING_PAYMENT,
            Order::STATUS_PAYMENT_UPLOADED,
            Order::STATUS_PROCESSING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ];

        if (! $status || ! in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $baseQuery = Order::where('buyer_id', Auth::id())->with(['items.product.seller', 'financial']);

        $ordersQuery = (clone $baseQuery)->latest();
        if ($status !== 'all') {
            $ordersQuery->where('status', $status);
        }

        $perPage = request('per_page', 50);

        if ($perPage !== 'all') {

            $perPage = (int) $perPage;

            if (!in_array($perPage, [50,100,300,500,1000])) {
                $perPage = 50;
            }
        }

        $perPage = (int) request('per_page', 50);

        if (!in_array($perPage, [50,100,300,500,1000])) {
            $perPage = 50;
        }

        $orders = $ordersQuery
            ->paginate($perPage)
            ->withQueryString();
        $statusCounts = collect($allowedStatuses)
            ->filter(fn ($itemStatus) => $itemStatus !== 'all')
            ->mapWithKeys(fn ($itemStatus) => [$itemStatus => (clone $baseQuery)->where('status', $itemStatus)->count()]);

        return view('orders.index', compact('orders', 'status', 'statusCounts'));
    }

    public function show($order_code) {
        $order = Order::where('order_code', $order_code)
                      ->orWhere('invoice_number', $order_code)
                      ->firstOrFail();

        $user = Auth::user();
        
        // PAKAI == BUKAN === BIAR STRING & INTEGER NGGAK BENTROK
        $isBuyer = $order->buyer_id == $user->id;
        $isSeller = $order->items()->whereHas('product', fn($query) => $query->where('seller_id', $user->id))->exists();

        if (!$isBuyer && !$isSeller && $user->role != 'admin') {
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI AKSES KE PESANAN INI.');
        }

        $order->load(['items.product.seller', 'financial']);
        return view('orders.show', compact('order'));
    }

    public function downloadReceiptPdf($order_code)
    {
        $order = Order::where('order_code', $order_code)
            ->orWhere('invoice_number', $order_code)
            ->firstOrFail();

        $user = Auth::user();
        $isBuyer = $order->buyer_id == $user->id;

        if (! $isBuyer) {
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI AKSES KE PESANAN INI.');
        }

        if ($order->status !== Order::STATUS_COMPLETED) {
            abort(403, 'Kwitansi hanya bisa diunduh setelah pesanan selesai.');
        }

        return app(PdfDocumentService::class)->downloadOrderReceipt(
            $order,
            ($order->invoice_number ?? 'kwitansi') . '.pdf'
        );
    }

    public function checkout(Request $request) {
        $cartItems = Cart::where('user_id', Auth::id())
            ->where('is_selected', true)
            ->with(['product.seller', 'product.category'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong atau belum ada produk yang dipilih!');
        }

        // Validasi stok semua item
        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->product->name} tidak mencukupi!");
            }
        }

        // Kelompokkan per seller (ala Shopee)
        $sellerGroups = $cartItems->groupBy(fn($item) => $item->product->seller_id);

        $feePercent = 0.02;
        $grandSubtotal = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
        $grandFee = round($grandSubtotal * $feePercent);
        $grandTotal = $grandSubtotal + $grandFee;

        // Hitung per-group
        $groupSummaries = $sellerGroups->map(function ($items) use ($feePercent) {
            $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
            $fee      = round($subtotal * $feePercent);
            return [
                'items'    => $items,
                'seller'   => $items->first()->product->seller,
                'subtotal' => $subtotal,
                'fee'      => $fee,
                'total'    => $subtotal + $fee,
            ];
        });

        return view('orders.checkout', compact(
            'cartItems', 'sellerGroups', 'groupSummaries',
            'grandSubtotal', 'grandFee', 'grandTotal'
        ));
    }

    public function store(Request $request) {
        $messages = [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'       => 'Metode pembayaran tidak valid.',
        ];

        $validated = $request->validate([
            'payment_method' => 'required|in:balance',
            'seller_notes'   => 'nullable|array',
            'seller_notes.*' => 'nullable|string|max:500',
        ], $messages);

        $cartItems = Cart::where('user_id', Auth::id())
            ->where('is_selected', true)
            ->with(['product.seller'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong atau belum ada produk yang dipilih!');
        }

        // Validasi stok semua item dulu
        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->product->name} tidak mencukupi!");
            }
        }

        $feePercent = 0.02;
        $grandSubtotal = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
        $grandFee      = round($grandSubtotal * $feePercent);
        $grandTotal    = $grandSubtotal + $grandFee;

        // Cek saldo sekali sebelum transaksi
        /** @var \App\Models\User $buyer */
        $buyer = Auth::user();
        if ((float) $buyer->balance < $grandTotal) {
            return back()->with('error', 'Saldo tidak mencukupi! Silakan isi saldo terlebih dahulu.');
        }

        // Kelompokkan per seller
        $sellerGroups = $cartItems->groupBy(fn($item) => $item->product->seller_id);
        $createdOrders = [];

        DB::transaction(function () use ($request, $buyer, $sellerGroups, $feePercent, $grandTotal, &$createdOrders) {
            // Kurangi saldo buyer sekali untuk total keseluruhan
            $buyer->deductBalance($grandTotal, "Checkout Multi-Seller " . now()->format('YmdHis'), null);

            foreach ($sellerGroups as $sellerId => $items) {
                $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
                $fee      = round($subtotal * $feePercent);
                $total    = $subtotal + $fee;

                $sellerNote = $request->input("seller_notes.{$sellerId}");

                $order = Order::create([
                    'buyer_id'       => $buyer->id,
                    'seller_id'      => $sellerId,
                    'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'status'         => Order::STATUS_PAYMENT_UPLOADED,
                    'payment_method' => 'balance',
                    'paid_at'        => now(),
                    'due_at'         => now()->addDays(2),
                    'delivery_notes' => $sellerNote,
                    'notes'          => $sellerNote,
                    'metadata'       => [
                        'fee_percent'     => $feePercent * 100,
                        'checkout_source' => 'cart_multi_seller',
                        'seller_count'    => $sellerGroups->count(),
                    ],
                ]);

                $order->financial()->create([
                    'subtotal'      => $subtotal,
                    'fee_amount'    => $fee,
                    'escrow_amount' => $subtotal,
                    'grand_total'   => $total,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => $item->product_id,
                        'seller_id'      => $item->product->seller_id,
                        'name_snapshot'  => $item->product->name,
                        'price_snapshot' => $item->product->price,
                        'product_name'   => $item->product->name,
                        'price'          => $item->product->price,
                        'quantity'       => $item->quantity,
                        'subtotal'       => $item->product->price * $item->quantity,
                        'status'         => 'pending',
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                }

                $createdOrders[] = $order;
            }

            // Hapus item yang sudah di-checkout dari keranjang
            Cart::where('user_id', $buyer->id)->where('is_selected', true)->delete();

            // Simpan order codes ke session untuk halaman sukses
            session(['last_order_codes' => collect($createdOrders)->pluck('order_code')->toArray()]);
        });

        // Jika hanya 1 seller, redirect ke detail order itu
        if (count($createdOrders) === 1) {
            $code = $createdOrders[0]->order_code ?? $createdOrders[0]->invoice_number;
            return redirect()->route('orders.show', $code)
                ->with('success', 'Pesanan berhasil dibuat dan sedang diproses!');
        }

        // Jika multi-seller, redirect ke daftar pesanan
        return redirect()->route('orders.index')
            ->with('success', count($createdOrders) . ' pesanan berhasil dibuat untuk ' . count($createdOrders) . ' penjual berbeda!');
    }

{
    $order = Order::where('order_code', $order_code)
        ->orWhere('invoice_number', $order_code)
        ->firstOrFail();

    abort_if($order->buyer_id != Auth::id(), 403);
    abort_if($order->status !== Order::STATUS_PENDING_PAYMENT, 422, 'Order sudah diproses.');

    /** @var User $user */
    $user = Auth::user();

    $request->validate([
        'payment_method' => 'required|in:balance',
    ]);

    $amount = (float) ($order->grand_total ?? $order->total_price ?? 0);

    if ($user->balance < $amount) {
        return back()->with('error', 'Saldo tidak mencukupi!');
    }

    DB::transaction(function () use ($order, $user, $amount) {

        $user->deductBalance(
            $amount,
            "Pembayaran Order #{$order->order_code}",
            $order->id
        );

        $order->update([
            'status' => Order::STATUS_PAYMENT_UPLOADED,
            'payment_method' => 'balance',
            'paid_at' => now(),
        ]);
    });

    return redirect()
        ->route('orders.show', $order->order_code ?? $order->invoice_number)
        ->with('success', 'Pembayaran berhasil!');
}
    public function store(Request $request) {
        $messages = [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'buyer_note.string' => 'Catatan pesanan harus berupa teks.',
            'buyer_note.max' => 'Catatan pesanan maksimal 1000 karakter.',
        ];

        $validated = $request->validate([
            'payment_method' => 'required|in:balance',
            'buyer_note' => 'nullable|string|max:1000',
        ], $messages);

        $cartItems = Cart::where('user_id', Auth::id())->where('is_selected', true)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong atau belum ada produk yang dipilih!');
        }

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->product->name} tidak mencukupi!");
            }
        }

        DB::transaction(function () use ($request, $cartItems) {
            $subtotal = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
            $fee = round($subtotal * 0.02);
            $grand_total = $subtotal + $fee; 

            $notesArray = [];
            foreach ($cartItems as $item) {
                if (!empty($item->notes)) {
                    $notesArray[] = $item->product->name . ': ' . $item->notes;
                }
            }
            $combinedNotes = implode("\n", $notesArray);

            $sellerIds = $cartItems->pluck('product.seller_id')->filter()->unique()->values();

            $orderData = [
                'buyer_id'       => Auth::id(),
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'status'         => Order::STATUS_PENDING_PAYMENT,
                'payment_method' => $request->payment_method,
                'delivery_notes' => $validated['buyer_note'] ?? null,
            ];

            if ($sellerIds->count() === 1) {
                $orderData['seller_id'] = $sellerIds->first();
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'total_price')) {
                $orderData['total_price'] = $grand_total;
            }

            if (Schema::hasColumn('orders', 'notes')) {
                $orderData['notes'] = $combinedNotes ?: null;
            }

            $order = Order::create($orderData);

            if (method_exists($order, 'financial')) {
                $order->financial()->create([
                    'subtotal' => $subtotal,
                    'fee_amount' => $fee,
                    'escrow_amount' => $subtotal,
                    'grand_total' => $grand_total,
                ]);
            }   

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'seller_id' => $item->product->seller_id,

                    'name_snapshot' => $item->product->name,
                    'price_snapshot' => $item->product->price,

                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', Auth::id())->where('is_selected', true)->delete();

            $identifier = $order->order_code ?? $order->invoice_number ?? $order->id;
            session(['last_order_code' => $identifier]);
        });

        return redirect()->route('orders.show', session('last_order_code'))
            ->with('success', 'Order berhasil dibuat!');
    }

    public function complete($order_code) {
        $order = Order::where('order_code', $order_code)->orWhere('invoice_number', $order_code)->firstOrFail();
        
        // Pakai != (bukan !==)
        abort_if($order->buyer_id != Auth::id(), 403);
        abort_if(!in_array($order->status, [Order::STATUS_PAYMENT_UPLOADED, Order::STATUS_PROCESSING], true), 422);

        $order->loadMissing('items.product.seller');

        DB::transaction(function () use ($order) {
            $order->update(['status' => Order::STATUS_COMPLETED, 'completed_at' => now()]);

            foreach ($order->items as $item) {
                $sellerAmount = $item->subtotal * 0.95;
                if ($item->product?->seller) {
                    $item->product->seller->addBalance($sellerAmount, "Penjualan Order #{$order->order_code}", $order->id);
                }
                $item->update(['delivery_status' => 'received']);

                $statistics = $item->product->statistics()->firstOrCreate([], [
                    'sold_count' => 0,
                    'rating_average' => 0,
                    'review_count' => 0,
                    'views_count' => 0,
                    'downloads_count' => 0,
                ]);

                $statistics->forceFill([
                    'sold_count' => (int) $statistics->sold_count + $item->quantity,
                ])->save();
            }
        });

        return redirect()->route('orders.show', $order->order_code ?? $order->invoice_number)
            ->with('success', 'Order diselesaikan! Saldo seller telah diperbarui.');
    }

    public function cancel($order_code) {
        $order = Order::where('order_code', $order_code)->orWhere('invoice_number', $order_code)->firstOrFail();
        
        // Pakai != (bukan !==)
        abort_if($order->buyer_id != Auth::id(), 403);
        abort_if(!in_array($order->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PAYMENT_UPLOADED], true), 422);

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            if ($order->status === Order::STATUS_PAYMENT_UPLOADED && $order->payment_method === 'balance') {
                $refundAmount = (float) ($order->grand_total ?? $order->total_price ?? 0);
                $order->buyer->addBalance($refundAmount, "Refund Order #{$order->order_code}", $order->id);
            }
            $order->update(['status' => Order::STATUS_CANCELLED]);
        });

        return redirect()->route('orders.index')->with('success', 'Order dibatalkan.');
    }

    public function uploadProof(Request $request, $order_code) {
        $order = Order::where('order_code', $order_code)->orWhere('invoice_number', $order_code)->firstOrFail();
        
        // Pakai != (bukan !==)
        abort_if($order->buyer_id != Auth::id(), 403);
        $request->validate(['payment_proof' => 'required|image|max:2048']);
        $file = $request->file('payment_proof');

        $filename = time() . '_' . $file->getClientOriginalName();

        $destination = public_path('storage/payment_proofs');

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move($destination, $filename);

        $path = 'payment_proofs/' . $filename;
        $order->update(['payment_proof' => $path, 'status' => Order::STATUS_PAYMENT_UPLOADED, 'paid_at' => now()]);
        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }
}
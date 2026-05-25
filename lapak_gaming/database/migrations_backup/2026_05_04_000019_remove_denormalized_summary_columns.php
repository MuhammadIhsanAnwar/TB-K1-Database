<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->backfillProductStatistics();
        $this->backfillOrderFinancials();
        $this->backfillWalletBalances();
        $this->backfillSellerLevelBenefits();

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table): void {
                $drop = [];

                foreach (['sold_count', 'rating_average', 'review_count', 'views_count', 'downloads_count'] as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $drop[] = $column;
                    }
                }

                if ($drop !== []) {
                    $table->dropColumn($drop);
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                $drop = [];

                foreach (['subtotal', 'fee_amount', 'escrow_amount', 'grand_total'] as $column) {
                    if (Schema::hasColumn('orders', $column)) {
                        $drop[] = $column;
                    }
                }

                if ($drop !== []) {
                    $table->dropColumn($drop);
                }
            });
        }

        if (Schema::hasTable('wallets')) {
            Schema::table('wallets', function (Blueprint $table): void {
                $drop = [];

                foreach (['balance', 'available_balance', 'locked_balance'] as $column) {
                    if (Schema::hasColumn('wallets', $column)) {
                        $drop[] = $column;
                    }
                }

                if ($drop !== []) {
                    $table->dropColumn($drop);
                }
            });
        }

        if (Schema::hasTable('seller_levels') && Schema::hasColumn('seller_levels', 'benefits')) {
            Schema::table('seller_levels', function (Blueprint $table): void {
                $table->dropColumn('benefits');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table): void {
                if (! Schema::hasColumn('products', 'sold_count')) {
                    $table->unsignedBigInteger('sold_count')->default(0);
                }

                if (! Schema::hasColumn('products', 'rating_average')) {
                    $table->decimal('rating_average', 4, 2)->default(0);
                }

                if (! Schema::hasColumn('products', 'review_count')) {
                    $table->unsignedInteger('review_count')->default(0);
                }

                if (! Schema::hasColumn('products', 'views_count')) {
                    $table->unsignedBigInteger('views_count')->default(0);
                }

                if (! Schema::hasColumn('products', 'downloads_count')) {
                    $table->unsignedBigInteger('downloads_count')->default(0);
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('orders', 'subtotal')) {
                    $table->decimal('subtotal', 14, 2)->default(0);
                }
                if (! Schema::hasColumn('orders', 'fee_amount')) {
                    $table->decimal('fee_amount', 14, 2)->default(0);
                }
                if (! Schema::hasColumn('orders', 'escrow_amount')) {
                    $table->decimal('escrow_amount', 14, 2)->default(0);
                }
                if (! Schema::hasColumn('orders', 'grand_total')) {
                    $table->decimal('grand_total', 14, 2)->default(0);
                }
            });
        }

        if (Schema::hasTable('wallets')) {
            Schema::table('wallets', function (Blueprint $table): void {
                if (! Schema::hasColumn('wallets', 'balance')) {
                    $table->decimal('balance', 14, 2)->default(0);
                }
                if (! Schema::hasColumn('wallets', 'available_balance')) {
                    $table->decimal('available_balance', 14, 2)->default(0);
                }
                if (! Schema::hasColumn('wallets', 'locked_balance')) {
                    $table->decimal('locked_balance', 14, 2)->default(0);
                }
            });
        }

        if (Schema::hasTable('seller_levels') && ! Schema::hasColumn('seller_levels', 'benefits')) {
            Schema::table('seller_levels', function (Blueprint $table): void {
                $table->json('benefits')->nullable();
            });
        }
    }

    private function backfillProductStatistics(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasTable('product_statistics')) {
            return;
        }

        $columns = array_values(array_filter(
            ['sold_count', 'rating_average', 'review_count', 'views_count', 'downloads_count'],
            fn (string $column): bool => Schema::hasColumn('products', $column)
        ));

        $products = DB::table('products')->select(array_merge(['id'], $columns))->get();

        foreach ($products as $product) {
            DB::table('product_statistics')->updateOrInsert(
                ['product_id' => $product->id],
                [
                    'sold_count' => (int) ($product->sold_count ?? 0),
                    'rating_average' => (float) ($product->rating_average ?? 0),
                    'review_count' => (int) ($product->review_count ?? 0),
                    'views_count' => (int) ($product->views_count ?? 0),
                    'downloads_count' => (int) ($product->downloads_count ?? 0),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function backfillOrderFinancials(): void
    {
        if (! Schema::hasTable('orders') || ! Schema::hasTable('order_financials')) {
            return;
        }

        $columns = array_values(array_filter(
            ['subtotal', 'fee_amount', 'escrow_amount', 'grand_total'],
            fn (string $column): bool => Schema::hasColumn('orders', $column)
        ));

        $orders = DB::table('orders')->select(array_merge(['id'], $columns))->get();

        foreach ($orders as $order) {
            DB::table('order_financials')->updateOrInsert(
                ['order_id' => $order->id],
                [
                    'subtotal' => (float) ($order->subtotal ?? 0),
                    'fee_amount' => (float) ($order->fee_amount ?? 0),
                    'escrow_amount' => (float) ($order->escrow_amount ?? 0),
                    'grand_total' => (float) ($order->grand_total ?? 0),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function backfillWalletBalances(): void
    {
        if (! Schema::hasTable('wallets') || ! Schema::hasTable('wallet_balances')) {
            return;
        }

        $columns = array_values(array_filter(
            ['balance', 'available_balance', 'locked_balance'],
            fn (string $column): bool => Schema::hasColumn('wallets', $column)
        ));

        $wallets = DB::table('wallets')->select(array_merge(['id'], $columns))->get();

        foreach ($wallets as $wallet) {
            DB::table('wallet_balances')->updateOrInsert(
                ['wallet_id' => $wallet->id],
                [
                    'balance' => (float) ($wallet->balance ?? 0),
                    'available_balance' => (float) ($wallet->available_balance ?? 0),
                    'locked_balance' => (float) ($wallet->locked_balance ?? 0),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function backfillSellerLevelBenefits(): void
    {
        if (! Schema::hasTable('seller_levels') || ! Schema::hasTable('seller_level_benefits')) {
            return;
        }

        if (! Schema::hasColumn('seller_levels', 'benefits')) {
            return;
        }

        $sellerLevels = DB::table('seller_levels')->select('id', 'benefits')->get();

        foreach ($sellerLevels as $sellerLevel) {
            $benefits = json_decode((string) $sellerLevel->benefits, true);

            if (! is_array($benefits)) {
                continue;
            }

            foreach (array_values($benefits) as $index => $benefit) {
                $benefit = trim((string) $benefit);

                if ($benefit === '') {
                    continue;
                }

                DB::table('seller_level_benefits')->updateOrInsert(
                    [
                        'seller_level_id' => $sellerLevel->id,
                        'sort_order' => $index,
                    ],
                    [
                        'benefit' => $benefit,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
};
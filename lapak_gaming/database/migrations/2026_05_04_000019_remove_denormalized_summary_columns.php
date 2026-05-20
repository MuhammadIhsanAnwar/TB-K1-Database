<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
};
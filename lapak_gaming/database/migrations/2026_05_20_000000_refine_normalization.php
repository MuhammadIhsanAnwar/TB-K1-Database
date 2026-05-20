<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                $drop = [];

                foreach (['is_seller', 'user_type'] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $drop[] = $column;
                    }
                }

                if (! empty($drop)) {
                    $table->dropColumn($drop);
                }
            });
        }

        if (Schema::hasTable('buyers') && ! Schema::hasColumn('buyers', 'user_id')) {
            Schema::table('buyers', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('sellers') && ! Schema::hasColumn('sellers', 'user_id')) {
            Schema::table('sellers', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'seller_id')) {
            Schema::table('order_items', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('seller_id');
            });
        }

        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'seller_id')) {
            Schema::table('reviews', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('seller_id');
            });
        }

        if (Schema::hasTable('wallet_transactions') && Schema::hasColumn('wallet_transactions', 'user_id')) {
            Schema::table('wallet_transactions', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (Schema::hasTable('product_comments')) {
            Schema::table('product_comments', function (Blueprint $table): void {
                $drop = [];

                foreach (['likes_count', 'replies_count'] as $column) {
                    if (Schema::hasColumn('product_comments', $column)) {
                        $drop[] = $column;
                    }
                }

                if (! empty($drop)) {
                    $table->dropColumn($drop);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'is_seller')) {
                    $table->boolean('is_seller')->default(false)->after('role');
                }

                if (! Schema::hasColumn('users', 'user_type')) {
                    $table->enum('user_type', ['buyer', 'seller', 'mixed'])->default('mixed')->after('role');
                }
            });
        }

        if (Schema::hasTable('buyers') && ! Schema::hasColumn('buyers', 'user_id')) {
            Schema::table('buyers', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('sellers') && ! Schema::hasColumn('sellers', 'user_id')) {
            Schema::table('sellers', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('order_items') && ! Schema::hasColumn('order_items', 'seller_id')) {
            Schema::table('order_items', function (Blueprint $table): void {
                $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('reviews') && ! Schema::hasColumn('reviews', 'seller_id')) {
            Schema::table('reviews', function (Blueprint $table): void {
                $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('wallet_transactions') && ! Schema::hasColumn('wallet_transactions', 'user_id')) {
            Schema::table('wallet_transactions', function (Blueprint $table): void {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('product_comments')) {
            Schema::table('product_comments', function (Blueprint $table): void {
                if (! Schema::hasColumn('product_comments', 'likes_count')) {
                    $table->unsignedInteger('likes_count')->default(0);
                }
                if (! Schema::hasColumn('product_comments', 'replies_count')) {
                    $table->unsignedInteger('replies_count')->default(0);
                }
            });
        }
    }
};

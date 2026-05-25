<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->backfillBuyerSellerLinks();

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

        if (Schema::hasTable('buyers')) {
            Schema::table('buyers', function (Blueprint $table): void {
                $drop = [];

                foreach (['name', 'username', 'email', 'email_verified_at', 'password', 'phone', 'avatar', 'status', 'suspended_at', 'remember_token'] as $column) {
                    if (Schema::hasColumn('buyers', $column)) {
                        $drop[] = $column;
                    }
                }

                if ($drop !== []) {
                    $table->dropColumn($drop);
                }
            });
        }

        if (Schema::hasTable('sellers') && ! Schema::hasColumn('sellers', 'user_id')) {
            Schema::table('sellers', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('sellers')) {
            Schema::table('sellers', function (Blueprint $table): void {
                $drop = [];

                foreach (['name', 'username', 'email', 'email_verified_at', 'password', 'phone', 'avatar', 'status', 'suspended_at', 'remember_token'] as $column) {
                    if (Schema::hasColumn('sellers', $column)) {
                        $drop[] = $column;
                    }
                }

                if ($drop !== []) {
                    $table->dropColumn($drop);
                }

                if (Schema::hasColumn('sellers', 'seller_level_id')) {
                    try {
                        $table->foreign('seller_level_id')->references('id')->on('seller_levels')->nullOnDelete();
                    } catch (\Throwable $exception) {
                        // constraint may already exist
                    }
                }
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

        if (Schema::hasTable('buyers')) {
            Schema::table('buyers', function (Blueprint $table): void {
                if (! Schema::hasColumn('buyers', 'name')) {
                    $table->string('name');
                }
                if (! Schema::hasColumn('buyers', 'username')) {
                    $table->string('username')->unique();
                }
                if (! Schema::hasColumn('buyers', 'email')) {
                    $table->string('email')->unique();
                }
                if (! Schema::hasColumn('buyers', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                }
                if (! Schema::hasColumn('buyers', 'password')) {
                    $table->string('password');
                }
                if (! Schema::hasColumn('buyers', 'phone')) {
                    $table->string('phone', 30)->nullable();
                }
                if (! Schema::hasColumn('buyers', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (! Schema::hasColumn('buyers', 'status')) {
                    $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
                }
                if (! Schema::hasColumn('buyers', 'suspended_at')) {
                    $table->timestamp('suspended_at')->nullable();
                }
                if (! Schema::hasColumn('buyers', 'remember_token')) {
                    $table->rememberToken();
                }
            });
        }

        if (Schema::hasTable('sellers') && ! Schema::hasColumn('sellers', 'user_id')) {
            Schema::table('sellers', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('sellers')) {
            Schema::table('sellers', function (Blueprint $table): void {
                if (! Schema::hasColumn('sellers', 'name')) {
                    $table->string('name');
                }
                if (! Schema::hasColumn('sellers', 'username')) {
                    $table->string('username')->unique();
                }
                if (! Schema::hasColumn('sellers', 'email')) {
                    $table->string('email')->unique();
                }
                if (! Schema::hasColumn('sellers', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                }
                if (! Schema::hasColumn('sellers', 'password')) {
                    $table->string('password');
                }
                if (! Schema::hasColumn('sellers', 'phone')) {
                    $table->string('phone', 30)->nullable();
                }
                if (! Schema::hasColumn('sellers', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (! Schema::hasColumn('sellers', 'seller_level_id')) {
                    $table->foreignId('seller_level_id')->nullable()->constrained('seller_levels')->nullOnDelete();
                }
                if (! Schema::hasColumn('sellers', 'status')) {
                    $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
                }
                if (! Schema::hasColumn('sellers', 'suspended_at')) {
                    $table->timestamp('suspended_at')->nullable();
                }
                if (! Schema::hasColumn('sellers', 'remember_token')) {
                    $table->rememberToken();
                }
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

    private function backfillBuyerSellerLinks(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $userColumns = ['id'];

        foreach (['username', 'email', 'name'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                $userColumns[] = $column;
            }
        }

        $users = DB::table('users')->select($userColumns)->get();

        $buyers = $this->mapIdentityRows('buyers', $users);
        $this->assignUserIds('buyers', $buyers);

        $sellers = $this->mapIdentityRows('sellers', $users);
        $this->assignUserIds('sellers', $sellers);
    }

    private function mapIdentityRows(string $table, $users): array
    {
        if (! Schema::hasTable($table)) {
            return [];
        }

        $legacyColumns = array_values(array_filter(
            ['username', 'email', 'name'],
            fn (string $column): bool => Schema::hasColumn($table, $column)
        ));

        if ($legacyColumns === []) {
            return [];
        }

        $rows = DB::table($table)->select(array_merge(['id'], $legacyColumns))->get();
        $assignments = [];

        foreach ($rows as $row) {
            $matchedUserId = null;

            foreach ($users as $user) {
                foreach ($legacyColumns as $column) {
                    if (isset($row->{$column}, $user->{$column}) && (string) $row->{$column} !== '' && strcasecmp((string) $row->{$column}, (string) $user->{$column}) === 0) {
                        $matchedUserId = $user->id;
                        break 2;
                    }
                }
            }

            $assignments[(int) $row->id] = $matchedUserId;
        }

        return $assignments;
    }

    private function assignUserIds(string $table, array $assignments): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'user_id')) {
            return;
        }

        foreach ($assignments as $rowId => $userId) {
            if ($userId === null) {
                continue;
            }

            DB::table($table)->where('id', $rowId)->update(['user_id' => $userId]);
        }
    }
};

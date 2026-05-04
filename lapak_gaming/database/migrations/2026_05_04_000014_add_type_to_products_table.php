<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'type')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->enum('type', ['topup', 'item', 'akun', 'voucher', 'gamekey'])
                    ->default('item')
                    ->after('stock');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'type')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('type');
            });
        }
    }
};

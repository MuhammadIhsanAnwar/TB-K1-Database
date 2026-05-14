<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        if (!Schema::hasColumn('orders', 'order_code')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->string('order_code')->nullable()->after('invoice_number');
            });
        }

        DB::table('orders')
            ->whereNull('order_code')
            ->orWhere('order_code', '')
            ->update(['order_code' => DB::raw('invoice_number')]);

        Schema::table('orders', function (Blueprint $table): void {
            $table->unique('order_code');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'order_code')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropColumn('order_code');
            });
        }
    }
};

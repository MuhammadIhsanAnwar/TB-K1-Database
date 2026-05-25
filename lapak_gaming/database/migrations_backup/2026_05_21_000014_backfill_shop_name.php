<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill `shop_name` for users who appear to be sellers but have no shop_name
        DB::table('users')
            ->whereNull('shop_name')
            ->where(function ($q) {
                $q->where('role', 'seller')
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('seller_status')->where('seller_status', '!=', 'none');
                  });
            })
            ->update(['shop_name' => DB::raw('name')]);
    }

    public function down(): void
    {
        // No-op: do not remove data on rollback.
    }
};

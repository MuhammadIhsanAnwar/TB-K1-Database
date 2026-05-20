<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_levels')) {
            return;
        }

        Schema::table('seller_levels', function (Blueprint $table): void {
            if (! Schema::hasColumn('seller_levels', 'minimum_orders')) {
                $table->unsignedInteger('minimum_orders')->default(0)->after('name');
            }

            if (! Schema::hasColumn('seller_levels', 'minimum_revenue')) {
                $table->decimal('minimum_revenue', 14, 2)->default(0)->after('minimum_orders');
            }

            if (! Schema::hasColumn('seller_levels', 'fee_percent')) {
                $table->decimal('fee_percent', 5, 2)->default(0)->after('minimum_revenue');
            }

            if (! Schema::hasColumn('seller_levels', 'badge_color')) {
                $table->string('badge_color')->default('slate')->after('fee_percent');
            }

            if (! Schema::hasColumn('seller_levels', 'auto_approve')) {
                $table->boolean('auto_approve')->default(false)->after('badge_color');
            }
        });

        // Backfill from legacy columns when available.
        if (Schema::hasColumn('seller_levels', 'min_sales') && Schema::hasColumn('seller_levels', 'minimum_orders')) {
            DB::statement('UPDATE seller_levels SET minimum_orders = COALESCE(min_sales, 0)');
        }

        if (Schema::hasColumn('seller_levels', 'commission_rate') && Schema::hasColumn('seller_levels', 'fee_percent')) {
            DB::statement('UPDATE seller_levels SET fee_percent = COALESCE(commission_rate, 0)');
        }
    }

    public function down(): void
    {
        // intentionally non-destructive for production compatibility
    }
};

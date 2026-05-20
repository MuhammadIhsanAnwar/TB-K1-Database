<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table): void {
            if (!Schema::hasColumn('carts', 'notes')) {
                $table->text('notes')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('carts', 'is_selected')) {
                $table->boolean('is_selected')->default(true)->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table): void {
            $table->dropColumn(['notes', 'is_selected']);
        });
    }
};

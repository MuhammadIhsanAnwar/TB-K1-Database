<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Intentionally left blank: seller_id is derived from product_id and is not stored.
    }

    public function down(): void
    {
        // Intentionally left blank.
    }
};

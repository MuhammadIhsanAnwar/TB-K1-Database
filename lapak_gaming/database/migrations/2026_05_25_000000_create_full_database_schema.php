<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('minimum_orders')->default(0);
            $table->decimal('minimum_revenue', 16, 2)->default(0);
            $table->decimal('fee_percent', 5, 2)->default(0);
            $table->string('badge_color')->nullable();
            $table->boolean('auto_approve')->default(false);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('buyer');
            $table->string('status')->default('active');
            $table->foreignId('seller_level_id')->nullable()->constrained('seller_levels')->nullOnDelete();
            $table->string('seller_status')->nullable();
            $table->text('seller_rejection_reason')->nullable();
            $table->string('google_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_photo')->nullable();
            $table->text('shop_description')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspend_reason')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->string('account_deletion_token')->nullable();
            $table->timestamp('account_deletion_token_sent_at')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->json('two_factor_methods')->nullable();
            $table->string('two_factor_google_secret')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->text('last_login_user_agent')->nullable();
            $table->string('last_login_device_hash', 128)->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_level_id')->nullable()->constrained('seller_levels')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('currency')->default('IDR');
            $table->timestamps();
        });

        Schema::create('wallet_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->unique()->constrained('wallets')->cascadeOnDelete();
            $table->decimal('balance', 16, 2)->default(0);
            $table->decimal('available_balance', 16, 2)->default(0);
            $table->decimal('locked_balance', 16, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->string('type');
            $table->string('direction');
            $table->decimal('amount', 16, 2);
            $table->decimal('balance_before', 16, 2);
            $table->decimal('balance_after', 16, 2);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('user_policy_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('policy_type');
            $table->string('version')->nullable();
            $table->timestamp('agreed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('consent_status')->default('agreed');
            $table->timestamps();
        });

        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('full_address')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('sale_price', 16, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->text('file_path')->nullable();
            $table->text('delivery_content')->nullable();
            $table->boolean('is_auto_delivery')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->string('type')->nullable();
            $table->string('status')->default('draft');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('product_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('sold_count')->default(0);
            $table->decimal('rating_average', 5, 2)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('order_code')->nullable()->unique();
            $table->string('status')->default('pending_payment');
            $table->string('payment_method')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('tracking_code')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('disputed_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('order_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->decimal('subtotal', 16, 2)->default(0);
            $table->decimal('fee_amount', 16, 2)->default(0);
            $table->decimal('escrow_amount', 16, 2)->default(0);
            $table->decimal('grand_total', 16, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name_snapshot');
            $table->decimal('price_snapshot', 16, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->json('delivery_data')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(0);
            $table->text('comment')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_comment_id')->nullable()->constrained('product_comments')->nullOnDelete();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->boolean('is_verified_buyer')->default(false);
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_comment_id')->constrained('product_comments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->boolean('is_selected')->default(true);
            $table->timestamps();
        });

        Schema::create('seller_level_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_level_id')->constrained('seller_levels')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('benefit');
            $table->timestamps();
            $table->unique(['seller_level_id', 'sort_order']);
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->string('link_url')->nullable();
            $table->string('position')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('marketplace_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('link')->nullable();
            $table->string('type')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->unsignedInteger('last_activity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('marketplace_notifications');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('seller_level_benefits');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('product_comments');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_financials');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_statistics');
        Schema::dropIfExists('products');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_policy_consents');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallet_balances');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('seller_levels');
    }
};

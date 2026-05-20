<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ItemkuProductsSeeder
 *
 * Di-generate otomatis oleh Itemku Scraper
 * Tanggal : 2026-05-20 09:42:34
 * Total   : 26 produk dari 3 kategori
 *
 * Cara pakai (jalankan SETELAH ItemkuCategoriesSeeder):
 *   php artisan db:seed --class=ItemkuProductsSeeder
 *
 * Atau lewat DatabaseSeeder:
 *   $this->call([ItemkuCategoriesSeeder::class, ItemkuProductsSeeder::class]);
 */
class ItemkuProductsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. PRODUCTS
        // ─────────────────────────────────────────────
        $products = [
            // #1 - Top Up Game
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Top Up Game',
                'slug'            => 'top-up-game',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #2 - Game Key
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Game Key',
                'slug'            => 'game-key',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #3 - Roblox Games
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Roblox Games',
                'slug'            => 'roblox-games',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #4 - Akun
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Akun',
                'slug'            => 'akun',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #5 - Voucher
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Voucher',
                'slug'            => 'voucher',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #6 - Koin Game
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Koin Game',
                'slug'            => 'koin-game',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #7 - Item
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Item',
                'slug'            => 'item',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #8 - Joki
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Joki',
                'slug'            => 'joki',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #9 - Top Up Login
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Top Up Login',
                'slug'            => 'top-up-login',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #10 - Streaming
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Streaming',
                'slug'            => 'streaming',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #11 - Live Show
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Live Show',
                'slug'            => 'live-show',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #12 - Pulsa & Utilitas
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Pulsa & Utilitas',
                'slug'            => 'pulsa-utilitas',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #13 - Aplikasi & Software
            [
                'category_slug'   => 'mobile-legends',
                'seller_user_id'  => 1,
                'name'            => 'Aplikasi & Software',
                'slug'            => 'aplikasi-software',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #14 - Top Up Game
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Top Up Game',
                'slug'            => 'top-up-game-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #15 - Game Key
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Game Key',
                'slug'            => 'game-key-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #16 - Roblox Games
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Roblox Games',
                'slug'            => 'roblox-games-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #17 - Akun
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Akun',
                'slug'            => 'akun-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #18 - Voucher
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Voucher',
                'slug'            => 'voucher-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #19 - Koin Game
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Koin Game',
                'slug'            => 'koin-game-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #20 - Item
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Item',
                'slug'            => 'item-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #21 - Joki
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Joki',
                'slug'            => 'joki-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #22 - Top Up Login
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Top Up Login',
                'slug'            => 'top-up-login-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #23 - Streaming
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Streaming',
                'slug'            => 'streaming-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #24 - Live Show
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Live Show',
                'slug'            => 'live-show-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #25 - Pulsa & Utilitas
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Pulsa & Utilitas',
                'slug'            => 'pulsa-utilitas-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            // #26 - Aplikasi & Software
            [
                'category_slug'   => 'free-fire',
                'seller_user_id'  => 1,
                'name'            => 'Aplikasi & Software',
                'slug'            => 'aplikasi-software-1',
                'description'     => '',
                'price'           => 0.00,
                'sale_price'      => null,
                'stock'           => 999,
                'type'            => 'topup',
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],
        ];

        $insertedCount = 0;
        $updatedCount  = 0;

        foreach ($products as $data) {
            // Ambil category_id dari slug
            $category = DB::table('categories')
                ->where('slug', $data['category_slug'])
                ->first();

            if (! $category) {
                $this->command->warn("⚠️  Kategori tidak ditemukan: {$data['category_slug']} — jalankan ItemkuCategoriesSeeder dulu!");
                continue;
            }

            $productData = array_merge(
                array_diff_key($data, array_flip(['category_slug', 'seller_user_id'])),
                [
                    'category_id' => $category->id,
                    'seller_id'   => $data['seller_user_id'],
                ]
            );

            $exists = DB::table('products')
                ->where('seller_id', $data['seller_user_id'])
                ->where('name', $data['name'])
                ->first();

            if ($exists) {
                // Update: hanya harga, stok, deskripsi
                DB::table('products')->where('id', $exists->id)->update([
                    'price'       => $productData['price'],
                    'sale_price'  => $productData['sale_price'],
                    'stock'       => $productData['stock'],
                    'description' => $productData['description'],
                    'updated_at'  => now(),
                ]);
                $updatedCount++;
            } else {
                DB::table('products')->insert($productData);
                $insertedCount++;
            }
        }

        $this->command->info("✅ Products: {$insertedCount} baru, {$updatedCount} diupdate");

        // ─────────────────────────────────────────────
        // 2. PRODUCT STATISTICS
        // ─────────────────────────────────────────────
        $statistics = [
            [
                'product_slug'    => 'top-up-game',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'game-key',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'roblox-games',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'akun',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'voucher',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'koin-game',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'item',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'joki',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'top-up-login',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'streaming',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'live-show',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'pulsa-utilitas',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'aplikasi-software',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'top-up-game-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'game-key-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'roblox-games-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'akun-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'voucher-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'koin-game-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'item-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'joki-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'top-up-login-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'streaming-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'live-show-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'pulsa-utilitas-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],

            [
                'product_slug'    => 'aplikasi-software-1',
                'sold_count'      => 0,
                'rating_average'  => 0.00,
                'review_count'    => 0,
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => '2026-05-20 09:42:34',
                'updated_at'      => '2026-05-20 09:42:34',
            ],
        ];

        $statsCount = 0;
        foreach ($statistics as $stat) {
            $product = DB::table('products')
                ->where('slug', $stat['product_slug'])
                ->first();

            if (! $product) {
                continue;
            }

            $statData = array_merge(
                array_diff_key($stat, array_flip(['product_slug'])),
                ['product_id' => $product->id]
            );

            DB::table('product_statistics')->updateOrInsert(
                ['product_id' => $product->id],
                $statData
            );
            $statsCount++;
        }

        $this->command->info("✅ Product Statistics: {$statsCount} records");
        $this->command->info("🎉 Total produk Itemku berhasil di-seed: 26");
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ItemkuCategoriesSeeder
 *
 * Di-generate otomatis oleh Itemku Scraper
 * Tanggal : 2026-05-20 09:42:34
 * Kategori: mobile-legends, free-fire, pubg-mobile
 *
 * Cara pakai:
 *   php artisan db:seed --class=ItemkuCategoriesSeeder
 *
 * CATATAN: Seeder ini menggunakan INSERT IGNORE agar tidak error
 *          jika slug sudah ada di database.
 */
class ItemkuCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'parent_id'   => null,
                'name'        => 'Mobile Legends',
                'slug'        => 'mobile-legends',
                'description' => 'Top up dan item Mobile Legends',
                'icon'        => null,
                'image'       => null,
                'sort_order'  => 1,
                'is_active'   => 1,
                'created_at'  => '2026-05-20 09:42:34',
                'updated_at'  => '2026-05-20 09:42:34',
            ],
            [
                'parent_id'   => null,
                'name'        => 'Free Fire',
                'slug'        => 'free-fire',
                'description' => 'Diamond dan item Free Fire',
                'icon'        => null,
                'image'       => null,
                'sort_order'  => 2,
                'is_active'   => 1,
                'created_at'  => '2026-05-20 09:42:34',
                'updated_at'  => '2026-05-20 09:42:34',
            ],
            [
                'parent_id'   => null,
                'name'        => 'PUBG Mobile',
                'slug'        => 'pubg-mobile',
                'description' => 'UC dan item PUBG Mobile',
                'icon'        => null,
                'image'       => null,
                'sort_order'  => 3,
                'is_active'   => 1,
                'created_at'  => '2026-05-20 09:42:34',
                'updated_at'  => '2026-05-20 09:42:34',
            ],
        ];

        foreach ($categories as $category) {
            // Gunakan updateOrInsert agar aman dijalankan berulang kali
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Kategori Itemku berhasil di-seed: ' . count($categories) . ' kategori');
    }
}

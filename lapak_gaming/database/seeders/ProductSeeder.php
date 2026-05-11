<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStatistic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::query()->where('role', 'seller')->first();
        $category = Category::query()->where('slug', 'top-up')->first() ?? Category::query()->first();

        if (! $seller || ! $category) {
            return;
        }

        $items = [
            ['name' => 'Diamond ML 86', 'price' => 25000, 'stock' => 999, 'featured' => true, 'trending' => true],
            ['name' => 'Voucher Steam 100K', 'price' => 98000, 'stock' => 250, 'featured' => true, 'trending' => false],
            ['name' => 'Akun Netflix Premium', 'price' => 45000, 'stock' => 50, 'featured' => false, 'trending' => true],
            ['name' => 'Lisensi Canva Pro', 'price' => 75000, 'stock' => 100, 'featured' => false, 'trending' => false],
        ];

        foreach ($items as $item) {
            $product = Product::create([
                'seller_id' => $seller->id,
                'category_id' => $category->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']).'-'.random_int(100, 999),
                'description' => 'Produk digital siap kirim dengan sistem escrow dan auto delivery.',
                'price' => $item['price'],
                'sale_price' => null,
                'stock' => $item['stock'],
                'delivery_content' => 'Gunakan area delivery untuk menaruh file, kode, atau instruksi.',
                'is_auto_delivery' => true,
                'is_featured' => $item['featured'],
                'is_trending' => $item['trending'],
                'status' => 'published',
            ]);

            $product->statistics()->create([
                'sold_count' => random_int(0, 120),
                'rating_average' => 4.8,
                'review_count' => 18,
                'views_count' => random_int(120, 4000),
                'downloads_count' => random_int(10, 500),
            ]);
        }
    }
}
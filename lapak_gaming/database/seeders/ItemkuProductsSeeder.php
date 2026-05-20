<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ItemkuProductsSeeder
 *
 * Di-generate otomatis oleh Itemku Scraper
 * Tanggal : 2026-05-20 09:42:34
 * Total   : 6500 produk dari 13 kategori
 *
 * Cara pakai (jalankan SETELAH CategorySeeder dan data user seller):
 *   php artisan db:seed --class=ItemkuProductsSeeder
 */
class ItemkuProductsSeeder extends Seeder
{
    private const PRODUCTS_PER_CATEGORY = 500;
    private const TARGET_SELLER_EMAIL = 'seller@lapakgeming.com';

    public function run(): void
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $sellerIds = $this->resolveSellerIds();

        if ($sellerIds === []) {
            $this->command->error('Tidak ada seller yang ditemukan. Jalankan seeder user/seller terlebih dahulu.');
            return;
        }

        $catalogs = $this->catalogs();
        $categorySlugs = array_values(array_unique(array_map(
            static fn (array $catalog): string => $catalog['category_slug'],
            $catalogs
        )));
        $categories = Category::query()->whereIn('slug', $categorySlugs)->get()->keyBy('slug');

        $insertedCount = 0;
        $updatedCount = 0;
        $statsCount = 0;

        foreach ($catalogs as $catalogIndex => $catalog) {
            $category = $categories->get($catalog['category_slug']);

            if (! $category) {
                $this->command->warn("Kategori tidak ditemukan: {$catalog['category_slug']}. Jalankan CategorySeeder dulu.");
                continue;
            }

            for ($sequence = 1; $sequence <= self::PRODUCTS_PER_CATEGORY; $sequence++) {
                $globalSeed = ($catalogIndex * self::PRODUCTS_PER_CATEGORY) + $sequence;
                $name = $this->buildProductName($catalog, $sequence);
                $slug = Str::slug($name) . '-' . sprintf('%04d', $globalSeed);
                $sellerId = $sellerIds[($globalSeed - 1) % count($sellerIds)];
                $imageUrl = $catalog['images'][($globalSeed - 1) % count($catalog['images'])];
                $price = $this->buildPrice($catalog, $sequence);
                $salePrice = $this->buildSalePrice($price, $sequence);
                $stock = $this->buildStock($catalog, $sequence);
                $viewsCount = $this->buildViewsCount($catalogIndex, $sequence);
                $downloadsCount = $this->buildDownloadsCount($catalogIndex, $sequence);
                $soldCount = $this->buildSoldCount($catalogIndex, $sequence);
                $ratingAverage = $this->buildRatingAverage($catalogIndex, $sequence);
                $reviewCount = $this->buildReviewCount($soldCount);

                $productData = [
                    'seller_id' => $sellerId,
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $this->buildDescription($catalog, $name),
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => $stock,
                    'file_path' => $imageUrl,
                    'delivery_content' => $catalog['delivery_content'],
                    'is_auto_delivery' => $catalog['is_auto_delivery'],
                    'is_featured' => $sequence % 10 === 0,
                    'is_trending' => $sequence % 6 === 0,
                    'status' => 'published',
                    'rating_average' => $ratingAverage,
                    'review_count' => $reviewCount,
                    'views_count' => $viewsCount,
                    'downloads_count' => $downloadsCount,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                $exists = DB::table('products')->where('slug', $slug)->exists();

                DB::table('products')->updateOrInsert(
                    ['slug' => $slug],
                    $productData
                );

                if ($exists) {
                    $updatedCount++;
                } else {
                    $insertedCount++;
                }

                $productId = DB::table('products')->where('slug', $slug)->value('id');

                if ($productId) {
                    DB::table('product_statistics')->updateOrInsert(
                        ['product_id' => $productId],
                        [
                            'sold_count' => $soldCount,
                            'rating_average' => $ratingAverage,
                            'review_count' => $reviewCount,
                            'views_count' => $viewsCount,
                            'downloads_count' => $downloadsCount,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ]
                    );

                    $statsCount++;
                }
            }
        }

        $this->command->info("Products Itemku: {$insertedCount} baru, {$updatedCount} diupdate");
        $this->command->info("Product Statistics: {$statsCount} records");
        $this->command->info('Total produk Itemku berhasil di-seed: 6500');
    }

    private function resolveSellerIds(): array
    {
        $sellerIds = User::query()
            ->where('role', 'seller')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $targetSellerId = User::query()
            ->where('email', self::TARGET_SELLER_EMAIL)
            ->value('id');

        if ($targetSellerId) {
            $sellerIds = array_values(array_unique(array_merge([$targetSellerId], $sellerIds)));
        }

        return $sellerIds;
    }

    private function catalogs(): array
    {
        $gamingImages = [
            'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?q=80&w=1200&auto=format&fit=crop',
        ];

        $esportsImages = [
            'https://images.unsplash.com/photo-1553481187-be93c21490a9?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1593305841991-05c297ba4575?q=80&w=1200&auto=format&fit=crop',
        ];

        $fantasyImages = [
            'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1542051841857-5f90071e7989?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1535223289827-42f1e9919769?q=80&w=1200&auto=format&fit=crop',
        ];

        $browserImages = [
            'https://images.unsplash.com/photo-1585647347483-22b66260dfff?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1511882150382-421056c89033?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
        ];

        $pixelImages = [
            'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1550259979-ed79b48d2a8d?q=80&w=1200&auto=format&fit=crop',
        ];

        return [
            [
                'category_slug' => 'mobile-legends-diamond',
                'name_pattern' => '{denom} Diamonds Mobile Legends - {variant} #{series}',
                'placeholders' => [
                    'denom' => [5, 11, 17, 22, 28, 44, 86, 172, 257, 344, 429, 514, 706, 875, 1003, 1440, 2195, 3688, 5532, 9288],
                    'variant' => ['Instan', 'Promo', 'Hemat', 'Cepat', 'Best Seller', 'Bonus', 'Event', 'Rekomendasi', 'Paket Hemat', 'Flash'],
                ],
                'images' => $gamingImages,
                'description' => 'Top up diamond Mobile Legends dengan proses instan dan harga kompetitif.',
                'delivery_content' => 'Masukkan User ID dan Server ID sebelum checkout. Pengiriman diproses otomatis setelah pembayaran terverifikasi.',
                'type' => 'topup',
                'is_auto_delivery' => true,
                'base_price' => 15000,
                'price_step' => 1200,
                'stock_min' => 500,
                'stock_max' => 9999,
            ],
            [
                'category_slug' => 'mobile-legends-akun',
                'name_pattern' => 'Akun Mobile Legends {rank} - {variant} #{series}',
                'placeholders' => [
                    'rank' => ['Epic', 'Legend', 'Mythic', 'Mythic Honor', 'Mythic Glory', 'Immortal'],
                    'variant' => ['Max Emblem', 'Skin Rare', 'Hero Lengkap', 'No Bind', 'Starter Farm', 'Aman', 'Bonus Diamond', 'Rekomendasi'],
                ],
                'images' => $gamingImages,
                'description' => 'Akun Mobile Legends siap login dengan variasi rank, hero, dan skin.',
                'delivery_content' => 'Akun dikirim melalui pesan order setelah pembayaran terverifikasi. Pastikan email aktif sebelum transaksi.',
                'type' => 'akun',
                'is_auto_delivery' => false,
                'base_price' => 150000,
                'price_step' => 25000,
                'stock_min' => 1,
                'stock_max' => 3,
            ],
            [
                'category_slug' => 'mobile-legends-joki',
                'name_pattern' => 'Joki Mobile Legends {from_rank} ke {to_rank} - {variant} #{series}',
                'placeholders' => [
                    'from_rank' => ['Epic', 'Legend', 'Mythic', 'Honor', 'Glory'],
                    'to_rank' => ['Legend', 'Mythic', 'Honor', 'Glory', 'Immortal'],
                    'variant' => ['Fast Track', 'Express', 'Aman', 'No Toxic', 'Winrate Boost', 'Premium', 'Prioritas', 'Rekomendasi'],
                ],
                'images' => $esportsImages,
                'description' => 'Layanan joki rank Mobile Legends dengan pengerjaan cepat dan aman.',
                'delivery_content' => 'Kirim detail akun dan target rank setelah checkout. Pengerjaan dilakukan bertahap sesuai antrean.',
                'type' => 'joki',
                'is_auto_delivery' => false,
                'base_price' => 8000,
                'price_step' => 1500,
                'stock_min' => 10,
                'stock_max' => 99,
            ],
            [
                'category_slug' => 'mobile-legends-gift-skin',
                'name_pattern' => 'Gift Skin Mobile Legends {hero} - {variant} #{series}',
                'placeholders' => [
                    'hero' => ['Lancelot', 'Alucard', 'Fanny', 'Gusion', 'Lesley', 'Granger', 'Nana', 'Benedetta', 'Saber', 'Wanwan'],
                    'variant' => ['Collector', 'Epic', 'Limited', 'Special', 'Rare', 'Promo', 'Bonus Diamond', 'Rekomendasi'],
                ],
                'images' => $gamingImages,
                'description' => 'Gift skin Mobile Legends untuk koleksi hero favorit dan hadiah event.',
                'delivery_content' => 'Pastikan akun tujuan sudah berteman dengan seller minimal 7 hari jika sistem gift resmi diperlukan.',
                'type' => 'item',
                'is_auto_delivery' => false,
                'base_price' => 85000,
                'price_step' => 12000,
                'stock_min' => 1,
                'stock_max' => 25,
            ],
            [
                'category_slug' => 'roblox-robux',
                'name_pattern' => '{denom} Robux Roblox - {variant} #{series}',
                'placeholders' => [
                    'denom' => [80, 100, 200, 400, 800, 1000, 1500, 2000, 2500, 5000, 10000, 25000],
                    'variant' => ['Instan', 'Promo', 'Cepat', 'Hemat', 'Best Seller', 'Flash', 'Paket Premium', 'Bulan Ini'],
                ],
                'images' => $browserImages,
                'description' => 'Top up Robux Roblox untuk avatar, game pass, dan kebutuhan komunitas.',
                'delivery_content' => 'Masukkan username Roblox dengan benar. Pengiriman dilakukan via gamepass atau group funds sesuai produk.',
                'type' => 'topup',
                'is_auto_delivery' => true,
                'base_price' => 25000,
                'price_step' => 3000,
                'stock_min' => 100,
                'stock_max' => 9999,
            ],
            [
                'category_slug' => 'roblox-items-pets',
                'name_pattern' => 'Item Roblox {item} - {variant} #{series}',
                'placeholders' => [
                    'item' => ['Buddha Fruit', 'Dragon Fruit', 'Spirit Orb', 'Gems Pack', 'Pet Bundle', 'Legendary Chest', 'Rainbow Pet', 'Diamond Egg', 'Limited Item', 'Boss Drop'],
                    'variant' => ['Permanent', 'Instan', 'Rare', 'Limited', 'Promo', 'Premium', 'Collector', 'Hemat'],
                ],
                'images' => $browserImages,
                'description' => 'Item, buah, dan pet Roblox untuk game populer seperti Blox Fruits dan Pet Simulator.',
                'delivery_content' => 'Setelah checkout, kirim nama game dan username Roblox agar item dapat diproses.',
                'type' => 'item',
                'is_auto_delivery' => false,
                'base_price' => 50000,
                'price_step' => 8000,
                'stock_min' => 10,
                'stock_max' => 300,
            ],
            [
                'category_slug' => 'roblox-akun',
                'name_pattern' => 'Akun Roblox {tier} - {variant} #{series}',
                'placeholders' => [
                    'tier' => ['Starter', 'Veteran', 'Premium', 'Limited', 'No Headless', 'Rare Inventory', 'Classic 2017', 'Tahun Lama'],
                    'variant' => ['Original', 'Aman', 'Ready Join', 'Trade Ready', 'Bonus Item', 'Rekomendasi'],
                ],
                'images' => $browserImages,
                'description' => 'Akun Roblox siap pakai dengan inventory menarik dan koleksi limited.',
                'delivery_content' => 'Akun dikirim setelah pembayaran terverifikasi. Pastikan email aktif untuk proses pengalihan jika dibutuhkan.',
                'type' => 'akun',
                'is_auto_delivery' => false,
                'base_price' => 120000,
                'price_step' => 20000,
                'stock_min' => 1,
                'stock_max' => 3,
            ],
            [
                'category_slug' => 'growtopia-dl-wl-gems',
                'name_pattern' => '{denom} Growtopia {currency} - {variant} #{series}',
                'placeholders' => [
                    'denom' => [1, 5, 10, 20, 50, 100, 250, 500, 1000, 2500, 5000, 10000],
                    'currency' => ['DL', 'WL', 'Gems'],
                    'variant' => ['Instan', 'Grosir', 'Hemat', 'Cepat', 'Best Seller', 'Promo', 'Rekomendasi'],
                ],
                'images' => $pixelImages,
                'description' => 'Diamond Lock, World Lock, dan Gems Growtopia untuk trading dan progres dunia.',
                'delivery_content' => 'Cantumkan World Name dan GrowID sebelum checkout. Transaksi disesuaikan dengan kebutuhan dunia Anda.',
                'type' => 'item',
                'is_auto_delivery' => true,
                'base_price' => 10000,
                'price_step' => 2000,
                'stock_min' => 100,
                'stock_max' => 9999,
            ],
            [
                'category_slug' => 'free-fire-diamond',
                'name_pattern' => '{denom} Diamonds Free Fire - {variant} #{series}',
                'placeholders' => [
                    'denom' => [5, 11, 50, 70, 140, 355, 720, 1440, 2180, 3640, 7290, 14580],
                    'variant' => ['Instan', 'Promo', 'Cepat', 'Hemat', 'Best Seller', 'Bonus', 'Event', 'Rekomendasi'],
                ],
                'images' => $esportsImages,
                'description' => 'Top up diamond Free Fire dengan proses cepat dan harga kompetitif.',
                'delivery_content' => 'Masukkan User ID Free Fire dengan teliti. Pengiriman diproses otomatis setelah pembayaran masuk.',
                'type' => 'topup',
                'is_auto_delivery' => true,
                'base_price' => 12000,
                'price_step' => 1000,
                'stock_min' => 500,
                'stock_max' => 9999,
            ],
            [
                'category_slug' => 'free-fire-akun',
                'name_pattern' => 'Akun Free Fire {tier} - {variant} #{series}',
                'placeholders' => [
                    'tier' => ['Old Season', 'Elite Pass', 'Bundle Langka', 'Sultan', 'Handal', 'Rank Master', 'Kolektor', 'Remaja Garena'],
                    'variant' => ['Aman', 'Original', 'Bonus Skin', 'Ready Login', 'Rekomendasi', 'Cepat', 'Murah'],
                ],
                'images' => $esportsImages,
                'description' => 'Akun Free Fire siap pakai dengan koleksi skin, bundle, dan rank menarik.',
                'delivery_content' => 'Akun dikirim manual setelah pembayaran terverifikasi. Pastikan data login dicatat dengan benar.',
                'type' => 'akun',
                'is_auto_delivery' => false,
                'base_price' => 100000,
                'price_step' => 18000,
                'stock_min' => 1,
                'stock_max' => 4,
            ],
            [
                'category_slug' => 'pubg-mobile-uc',
                'name_pattern' => '{denom} UC PUBG Mobile - {variant} #{series}',
                'placeholders' => [
                    'denom' => [30, 60, 325, 660, 975, 1320, 1800, 2180, 3850, 5650, 8100, 16200],
                    'variant' => ['Instan', 'Promo', 'Cepat', 'Hemat', 'Best Seller', 'Bonus', 'Event', 'Rekomendasi'],
                ],
                'images' => $esportsImages,
                'description' => 'Top up UC PUBG Mobile untuk Royale Pass, crate, dan kebutuhan in-game.',
                'delivery_content' => 'Masukkan Player ID dan server dengan benar. Pengiriman dilakukan otomatis setelah pembayaran terkonfirmasi.',
                'type' => 'topup',
                'is_auto_delivery' => true,
                'base_price' => 15000,
                'price_step' => 1500,
                'stock_min' => 300,
                'stock_max' => 9999,
            ],
            [
                'category_slug' => 'genshin-impact-top-up',
                'name_pattern' => '{denom} Genesis Crystal Genshin Impact - {variant} #{series}',
                'placeholders' => [
                    'denom' => [60, 120, 300, 980, 1980, 3280, 6480, 9800, 12800, 19800],
                    'variant' => ['Instan', 'Promo', 'Cepat', 'Hemat', 'Best Seller', 'Bonus Welkin', 'Event', 'Rekomendasi'],
                ],
                'images' => $fantasyImages,
                'description' => 'Top up Genesis Crystal Genshin Impact untuk gacha karakter dan senjata favorit.',
                'delivery_content' => 'Cantumkan UID dan server sebelum checkout. Pengisian diproses otomatis setelah pembayaran terverifikasi.',
                'type' => 'topup',
                'is_auto_delivery' => true,
                'base_price' => 50000,
                'price_step' => 7000,
                'stock_min' => 100,
                'stock_max' => 9999,
            ],
            [
                'category_slug' => 'valorant-points',
                'name_pattern' => '{denom} Valorant Points - {variant} #{series}',
                'placeholders' => [
                    'denom' => [475, 1000, 2050, 3650, 5350, 7150, 9550, 12000],
                    'variant' => ['Instan', 'Promo', 'Cepat', 'Hemat', 'Best Seller', 'Bonus', 'Event', 'Rekomendasi'],
                ],
                'images' => $esportsImages,
                'description' => 'Top up Valorant Points untuk bundle skin, battle pass, dan kebutuhan Riot.',
                'delivery_content' => 'Masukkan Riot ID dan tagline dengan benar. Proses pengiriman dilakukan sesudah pembayaran diterima.',
                'type' => 'topup',
                'is_auto_delivery' => true,
                'base_price' => 20000,
                'price_step' => 2500,
                'stock_min' => 300,
                'stock_max' => 9999,
            ],
        ];
    }

    private function buildProductName(array $catalog, int $sequence): string
    {
        $replacements = [
            '{series}' => sprintf('%03d', $sequence),
        ];

        $divisor = 1;

        foreach ($catalog['placeholders'] as $placeholder => $values) {
            $valueIndex = intdiv($sequence - 1, $divisor) % count($values);
            $replacements['{' . $placeholder . '}'] = $values[$valueIndex];
            $divisor *= max(1, count($values));
        }

        return strtr($catalog['name_pattern'], $replacements);
    }

    private function buildDescription(array $catalog, string $name): string
    {
        return $catalog['description'] . ' ' . $name . ' cocok untuk transaksi cepat, aman, dan relevan dengan katalog Itemku.';
    }

    private function buildPrice(array $catalog, int $sequence): int
    {
        $price = $catalog['base_price'] + (($sequence - 1) % 25) * $catalog['price_step'];

        return (int) (round($price / 100) * 100);
    }

    private function buildSalePrice(int $price, int $sequence): ?int
    {
        if ($sequence % 5 !== 0) {
            return null;
        }

        $salePrice = (int) (round(($price * 92) / 100 / 100) * 100);

        return $salePrice >= $price ? $price - 1000 : $salePrice;
    }

    private function buildStock(array $catalog, int $sequence): int
    {
        $range = max(0, $catalog['stock_max'] - $catalog['stock_min']);

        if ($range === 0) {
            return $catalog['stock_min'];
        }

        return $catalog['stock_min'] + (($sequence * 37) % ($range + 1));
    }

    private function buildViewsCount(int $catalogIndex, int $sequence): int
    {
        return 150 + (($sequence * 137 + $catalogIndex * 53) % 9000);
    }

    private function buildDownloadsCount(int $catalogIndex, int $sequence): int
    {
        return 10 + (($sequence * 29 + $catalogIndex * 11) % 480);
    }

    private function buildSoldCount(int $catalogIndex, int $sequence): int
    {
        return 5 + (($sequence * 17 + $catalogIndex * 7) % 120);
    }

    private function buildRatingAverage(int $catalogIndex, int $sequence): float
    {
        $rating = 4.2 + ((($sequence + $catalogIndex) % 8) * 0.1);

        return (float) min(5.0, round($rating, 2));
    }

    private function buildReviewCount(int $soldCount): int
    {
        return max(0, (int) floor($soldCount * 0.35));
    }
}
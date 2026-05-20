<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 10 Game & Brand Populer sesuai Itemku.com
        $categoriesData = [
            [
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'image' => 'app/public/ikon-kategori/topup-game.webp',
                'icon' => '⚔️',
                'children' => [
                    ['name' => 'Mobile Legends - Diamond', 'slug' => 'mobile-legends-diamond', 'icon' => '💎'],
                    ['name' => 'Mobile Legends - Akun', 'slug' => 'mobile-legends-akun', 'icon' => '👤'],
                    ['name' => 'Mobile Legends - Joki', 'slug' => 'mobile-legends-joki', 'icon' => '🏆'],
                    ['name' => 'Mobile Legends - Gift Skin', 'slug' => 'mobile-legends-gift-skin', 'icon' => '🎁'],
                ]
            ],
            [
                'name' => 'Roblox',
                'slug' => 'roblox',
                'image' => 'app/public/ikon-kategori/roblox-games.svg',
                'icon' => '🧱',
                'children' => [
                    ['name' => 'Roblox - Robux', 'slug' => 'roblox-robux', 'icon' => '💎'],
                    ['name' => 'Roblox - Items & Pets', 'slug' => 'roblox-items-pets', 'icon' => '🐶'],
                    ['name' => 'Roblox - Akun', 'slug' => 'roblox-akun', 'icon' => '👤'],
                ]
            ],
            [
                'name' => 'Growtopia',
                'slug' => 'growtopia',
                'image' => 'app/public/ikon-kategori/koin-game.webp',
                'icon' => '🌱',
                'children' => [
                    ['name' => 'Growtopia - DL / WL / Gems', 'slug' => 'growtopia-dl-wl-gems', 'icon' => '🪙'],
                    ['name' => 'Growtopia - Items', 'slug' => 'growtopia-items', 'icon' => '🧩'],
                    ['name' => 'Growtopia - Akun', 'slug' => 'growtopia-akun', 'icon' => '👤'],
                ]
            ],
            [
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'image' => 'app/public/ikon-kategori/topup-game.webp',
                'icon' => '🔥',
                'children' => [
                    ['name' => 'Free Fire - Diamond', 'slug' => 'free-fire-diamond', 'icon' => '💎'],
                    ['name' => 'Free Fire - Akun', 'slug' => 'free-fire-akun', 'icon' => '👤'],
                    ['name' => 'Free Fire - Joki', 'slug' => 'free-fire-joki', 'icon' => '🏆'],
                ]
            ],
            [
                'name' => 'PUBG Mobile',
                'slug' => 'pubg-mobile',
                'image' => 'app/public/ikon-kategori/topup-login.svg',
                'icon' => '🪂',
                'children' => [
                    ['name' => 'PUBG Mobile - UC', 'slug' => 'pubg-mobile-uc', 'icon' => '💵'],
                    ['name' => 'PUBG Mobile - Akun', 'slug' => 'pubg-mobile-akun', 'icon' => '👤'],
                ]
            ],
            [
                'name' => 'Genshin Impact',
                'slug' => 'genshin-impact',
                'image' => 'app/public/ikon-kategori/topup-game.webp',
                'icon' => '✨',
                'children' => [
                    ['name' => 'Genshin Impact - Top Up', 'slug' => 'genshin-impact-top-up', 'icon' => '💫'],
                    ['name' => 'Genshin Impact - Akun', 'slug' => 'genshin-impact-akun', 'icon' => '👤'],
                ]
            ],
            [
                'name' => 'Valorant',
                'slug' => 'valorant',
                'image' => 'app/public/ikon-kategori/topup-game.webp',
                'icon' => '🎯',
                'children' => [
                    ['name' => 'Valorant - Points', 'slug' => 'valorant-points', 'icon' => '💳'],
                    ['name' => 'Valorant - Akun', 'slug' => 'valorant-akun', 'icon' => '👤'],
                    ['name' => 'Valorant - Joki', 'slug' => 'valorant-joki', 'icon' => '🏆'],
                ]
            ],
            [
                'name' => 'Steam Wallet',
                'slug' => 'steam-wallet',
                'image' => 'app/public/ikon-kategori/voucher.webp',
                'icon' => '🎮',
                'children' => [
                    ['name' => 'Steam - Voucher IDR', 'slug' => 'steam-voucher-idr', 'icon' => '🎟️'],
                    ['name' => 'Steam - Voucher USD', 'slug' => 'steam-voucher-usd', 'icon' => '💵'],
                ]
            ],
            [
                'name' => 'Netflix',
                'slug' => 'netflix',
                'image' => 'app/public/ikon-kategori/streaming.webp',
                'icon' => '📺',
                'children' => [
                    ['name' => 'Netflix - Akun Premium', 'slug' => 'netflix-akun-premium', 'icon' => '🔑'],
                ]
            ],
            [
                'name' => 'Spotify',
                'slug' => 'spotify',
                'image' => 'app/public/ikon-kategori/streaming.webp',
                'icon' => '🎵',
                'children' => [
                    ['name' => 'Spotify - Premium Plan', 'slug' => 'spotify-premium-plan', 'icon' => '🔑'],
                ]
            ]
        ];

        $hasIconColumn = Schema::hasColumn('categories', 'icon');
        $hasImageColumn = Schema::hasColumn('categories', 'image');
        $keepIds = [];
        $sortOrder = 0;

        foreach ($categoriesData as $parentEntry) {
            $payload = [
                'name' => $parentEntry['name'],
                'sort_order' => $sortOrder++,
                'is_active' => true,
                'parent_id' => null,
            ];

            if ($hasIconColumn) {
                $payload['icon'] = $parentEntry['icon'];
            }

            if ($hasImageColumn) {
                $payload['image'] = $parentEntry['image'];
            }

            $parentCategory = Category::updateOrCreate(
                ['slug' => $parentEntry['slug']],
                $payload
            );

            $keepIds[] = $parentCategory->id;

            // Seed children categories (subcategories)
            if (isset($parentEntry['children'])) {
                foreach ($parentEntry['children'] as $childEntry) {
                    $childPayload = [
                        'name' => $childEntry['name'],
                        'sort_order' => $sortOrder++,
                        'is_active' => true,
                        'parent_id' => $parentCategory->id,
                    ];

                    if ($hasIconColumn) {
                        $childPayload['icon'] = $childEntry['icon'];
                    }

                    if ($hasImageColumn) {
                        $childPayload['image'] = null; // Subcategories don't necessarily have parent-level cover photos
                    }

                    $childCategory = Category::updateOrCreate(
                        ['slug' => $childEntry['slug']],
                        $childPayload
                    );

                    $keepIds[] = $childCategory->id;
                }
            }
        }

        // Cleanup obsolete categories
        $fallbackCategoryId = Category::query()->where('slug', 'mobile-legends')->value('id');
        $obsoleteCategoryIds = Category::query()->whereNotIn('id', $keepIds)->pluck('id');

        if ($fallbackCategoryId && $obsoleteCategoryIds->isNotEmpty() && Schema::hasTable('products')) {
            Product::query()
                ->whereIn('category_id', $obsoleteCategoryIds)
                ->update(['category_id' => $fallbackCategoryId]);
        }

        if ($obsoleteCategoryIds->isNotEmpty()) {
            Category::query()->whereIn('id', $obsoleteCategoryIds)->delete();
        }
    }
}

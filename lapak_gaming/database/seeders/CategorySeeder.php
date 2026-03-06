<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Game Items',
                'slug' => 'game-items',
                'description' => 'Item-item untuk berbagai game online',
                'icon' => '🎮',
                'color' => '#f97316',
                'parent_id' => null,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'description' => 'Diamond, Skin, dan item Mobile Legends',
                'icon' => '⚔️',
                'color' => '#7c3aed',
                'parent_id' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'PUBG Mobile',
                'slug' => 'pubg-mobile',
                'description' => 'UC dan item PUBG Mobile',
                'icon' => '🔫',
                'color' => '#06b6d4',
                'parent_id' => 1,
                'sort_order' => 2,
            ],
            [
                'name' => 'NFT Gaming',
                'slug' => 'nft-gaming',
                'description' => 'NFT dan in-game assets',
                'icon' => '💎',
                'color' => '#ec4899',
                'parent_id' => 1,
                'sort_order' => 3,
            ],
            [
                'name' => 'Vouchers',
                'slug' => 'vouchers',
                'description' => 'Code voucher dan top-up',
                'icon' => '🎟️',
                'color' => '#10b981',
                'parent_id' => null,
                'sort_order' => 2,
            ],
            [
                'name' => 'Game Vouchers',
                'slug' => 'game-vouchers',
                'description' => 'Code voucher untuk game',
                'icon' => '🎮',
                'color' => '#f59e0b',
                'parent_id' => 5,
                'sort_order' => 1,
            ],
            [
                'name' => 'Streaming Service',
                'slug' => 'streaming-service',
                'description' => 'Voucher streaming dan entertainment',
                'icon' => '🎬',
                'color' => '#ef4444',
                'parent_id' => 5,
                'sort_order' => 2,
            ],
            [
                'name' => 'Accounts',
                'slug' => 'game-accounts',
                'description' => 'Game accounts dengan karakter/skin',
                'icon' => '👤',
                'color' => '#3b82f6',
                'parent_id' => null,
                'sort_order' => 3,
            ],
            [
                'name' => 'MMORPG Accounts',
                'slug' => 'mmorpg-accounts',
                'description' => 'Akun MMORPG dengan level tinggi',
                'icon' => '🗡️',
                'color' => '#8b5cf6',
                'parent_id' => 8,
                'sort_order' => 1,
            ],
            [
                'name' => 'Marketplace Accounts',
                'slug' => 'market-accounts',
                'description' => 'E-commerce & marketplace aged accounts',
                'icon' => '🛒',
                'color' => '#14b8a6',
                'parent_id' => 8,
                'sort_order' => 2,
            ],
            [
                'name' => 'Digital Services',
                'slug' => 'digital-services',
                'description' => 'Layanan digital dan konsultasi',
                'icon' => '💼',
                'color' => '#6366f1',
                'parent_id' => null,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

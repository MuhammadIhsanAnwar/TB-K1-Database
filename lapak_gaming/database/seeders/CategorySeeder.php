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
        $categories = [
            ['name' => 'Top Up Game', 'slug' => 'top-up-game', 'image' => 'app/public/ikon-kategori/topup-game.webp', 'icon' => '⚡'],
            ['name' => 'Game Key', 'slug' => 'game-key', 'image' => 'app/public/ikon-kategori/game-key.webp', 'icon' => '🗝️'],
            ['name' => 'Roblox Games', 'slug' => 'roblox-games', 'image' => 'app/public/ikon-kategori/roblox-games.svg', 'icon' => '🎮'],
            ['name' => 'Akun', 'slug' => 'akun', 'image' => 'app/public/ikon-kategori/akun.svg', 'icon' => '👤'],
            ['name' => 'Voucher', 'slug' => 'voucher', 'image' => 'app/public/ikon-kategori/voucher.webp', 'icon' => '🎫'],
            ['name' => 'Koin Game', 'slug' => 'koin-game', 'image' => 'app/public/ikon-kategori/koin-game.webp', 'icon' => '🪙'],
            ['name' => 'Item', 'slug' => 'item', 'image' => 'app/public/ikon-kategori/item.webp', 'icon' => '🧩'],
            ['name' => 'Joki', 'slug' => 'joki', 'image' => 'app/public/ikon-kategori/joki.svg', 'icon' => '🏆'],
            ['name' => 'Top Up Login', 'slug' => 'top-up-login', 'image' => 'app/public/ikon-kategori/topup-login.svg', 'icon' => '🔐'],
            ['name' => 'Streaming', 'slug' => 'streaming', 'image' => 'app/public/ikon-kategori/streaming.webp', 'icon' => '📺'],
            ['name' => 'Live Show', 'slug' => 'live-show', 'image' => 'app/public/ikon-kategori/live-show.webp', 'icon' => '🎤'],
            ['name' => 'Pulsa & Utilitas', 'slug' => 'pulsa-utilitas', 'image' => 'app/public/ikon-kategori/pulsa-utilitas.webp', 'icon' => '📱'],
            ['name' => 'Aplikasi & Software', 'slug' => 'aplikasi-software', 'image' => 'app/public/ikon-kategori/aplikasi-software.svg', 'icon' => '💻'],
        ];

        $hasIconColumn = Schema::hasColumn('categories', 'icon');
        $hasImageColumn = Schema::hasColumn('categories', 'image');
        $keepIds = [];

        foreach ($categories as $i => $entry) {
            $payload = [
                'name' => $entry['name'],
                'sort_order' => $i,
                'is_active' => true,
                'parent_id' => null,
            ];

            if ($hasIconColumn) {
                $payload['icon'] = $entry['icon'];
            }

            if ($hasImageColumn) {
                $payload['image'] = $entry['image'];
            }

            $category = Category::updateOrCreate(
                ['slug' => $entry['slug']],
                $payload
            );

            $keepIds[] = $category->id;
        }

        $fallbackCategoryId = Category::query()->where('slug', 'top-up-game')->value('id');
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

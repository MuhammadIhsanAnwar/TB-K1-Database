<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class CategorySeederFromExcel extends Seeder
{
    public function run(): void
    {
        $hasIconColumn = Schema::hasColumn('categories', 'icon');
        $hasImageColumn = Schema::hasColumn('categories', 'image');

        // Data kategori baru dari Excel (menggantikan 6 kategori lama)
        $newCategories = [
            [
                'name' => 'Top Up',
                'slug' => 'top-up-game',
                'icon' => 'app/public/ikon-kategori/topup-game.svg',
                'children' => [
                    'Mobile Legends',
                    'Zepeto',
                    'Blood Strike',
                    'Valorant',
                    'Garena Free Fire',
                    'Honkai: Star Rail',
                    'Magic Chess: Go Go',
                    'LivU',
                    'Genshin Impact',
                    'PUBG Mobile',
                    'Garena Free Fire Max',
                    'Honor of Kings',
                ]
            ],
            [
                'name' => 'Game Key',
                'slug' => 'game-key',
                'icon' => 'app/public/ikon-kategori/game-key.svg',
                'children' => [
                    '911 Operator',
                    'ARK: Survival Ascended',
                    'Assassin\'s Creed® Odyssey',
                    'Palworld',
                    'Age of Empires II: Definitive Edition',
                    'Age of Empires III: Definitive Edition',
                    'Arma 3',
                    'Assassin\'s Creed Shadows',
                    '.hack//G.U. Last Recode',
                ]
            ],
            [
                'name' => 'Roblox Games',
                'slug' => 'roblox-games',
                'icon' => 'app/public/ikon-kategori/roblox-games.svg',
                'children' => [
                    'Fish It!',
                    'Steal A Brainrot',
                    'Blade Ball',
                    'Bee Swarm Simulator',
                    'Blox Fruits',
                    'Escape Tsunami for Brainrots',
                    'Grow A Garden',
                ]
            ],
            [
                'name' => 'Akun',
                'slug' => 'akun',
                'icon' => 'app/public/ikon-kategori/akun.svg',
                'children' => [
                    'Blox Fruits',
                    'Dead Rails',
                    'Steal A Brainrot',
                    'Garena Free Fire',
                    'Honkai: Star Rail',
                    'Mobile Legends',
                    'Genshin Impact',
                    'One Piece Bounty Rush',
                ]
            ],
        ];

        // Kategori lama yang harus dipertahankan (7 kategori)
        $keepCategories = [
            [
                'name' => 'Voucher',
                'slug' => 'voucher',
                'icon' => 'app/public/ikon-kategori/voucher.svg',
                'children' => [
                    'Steam',
                    'Roblox',
                    'Google Play Gift Card',
                    'ExitLag',
                    'Playstation Network Card',
                    'Cherry Credits',
                    'Nintendo',
                    'Razer Gold',
                    'Valorant',
                    'Redfinger',
                    'VSPhone',
                    'Point Blank Beyond Limits',
                ]
            ],
            [
                'name' => 'Koin Game',
                'slug' => 'koin-game',
                'icon' => 'app/public/ikon-kategori/koin-game.svg',
                'children' => [
                    'Growtopia',
                    'Seal Online Blades of Destiny',
                    'Toram Online',
                    'Roblox',
                    'Albion Online',
                    'Pet Simulator 99!',
                    'Grow A Garden',
                    'Blade Ball',
                    'Dragon Nest Classic Sea',
                    'Fisch',
                ]
            ],
            [
                'name' => 'Item',
                'slug' => 'item',
                'icon' => 'app/public/ikon-kategori/item.svg',
                'children' => [
                    'Dota 2',
                    'Roblox',
                    'Blox Fruits',
                    'Grow A Garden',
                    'Bubble Gum Simulator Infinity',
                    'Survive The Killer',
                    'Murder Mystery 2',
                    'Adopt Me Trading Hub',
                    'Fisch',
                    'Blue Lock Rivals',
                ]
            ],
            [
                'name' => 'Joki',
                'slug' => 'joki',
                'icon' => 'app/public/ikon-kategori/joki.svg',
                'children' => [
                    'Mobile Legends',
                    'Genshin Impact',
                    'Blox Fruits',
                    'Honkai: Star Rail',
                    'Anime Adventures',
                    'Anime Last Stand',
                    'Sol\'s RNG',
                    'Fisch',
                    'Anime Reborn',
                    'Jujutsu Infinite',
                ]
            ],
            [
                'name' => 'Top Up Login',
                'slug' => 'top-up-login',
                'icon' => 'app/public/ikon-kategori/topup-login.webp',
                'children' => [
                    'Roblox',
                    'Genshin Impact',
                    'eFootball Mobile',
                    'Wuthering Waves',
                    'Pokemon GO',
                    'Honkai: Star Rail',
                    'Clash Royale',
                    'Hay Day',
                    'Tree of Savior: Neverland',
                    'Zepeto',
                ]
            ],
            [
                'name' => 'Streaming',
                'slug' => 'streaming',
                'icon' => 'app/public/ikon-kategori/streaming.svg',
                'children' => [
                    'Spotify',
                    'Viu',
                    'YouTube Premium',
                    'Apple Music',
                    'Disney+ Hotstar',
                    'iQIYI',
                    'WeTV',
                    'Bilibili.tv',
                    'Netflix',
                    'Loklok',
                ]
            ],
            [
                'name' => 'Live Show',
                'slug' => 'live-show',
                'icon' => 'app/public/ikon-kategori/live-show.svg',
                'children' => [
                    'Poppo Live',
                    'BIGO Live',
                    'Papaya Live',
                    'Lemo',
                    'WeSing',
                    'MixU',
                    'MLiveU',
                    'StarMaker',
                    'Tango',
                    'Bermuda',
                ]
            ],
            [
                'name' => 'Pulsa & Utilitas',
                'slug' => 'pulsa-utilitas',
                'icon' => 'app/public/ikon-kategori/pulsa-utilitas.svg',
                'children' => [
                    'Axis',
                    'Token PLN',
                    'Telkomsel',
                    'Tri',
                    'Indosat Ooredoo',
                    'by.U',
                    'Alfamart',
                    'Smartfren',
                    'Wifi.id',
                    'XL',
                ]
            ],
            [
                'name' => 'Aplikasi & Software',
                'slug' => 'aplikasi-software',
                'icon' => 'app/public/ikon-kategori/aplikasi-software.svg',
                'children' => [
                    'Redfinger',
                    'Canva',
                    'ChatGPT',
                    'VSPhone',
                    'Express VPN',
                    'Alight Motion',
                    'HideMyAss VPN',
                    'Discord',
                    'ExitLag',
                    'Zoom Cloud Meetings',
                ]
            ],
        ];

        // Array untuk menyimpan ID kategori yang harus dipertahankan
        $keepIds = [];

        // Process kategori baru dari Excel - akan menggantikan data lama
        foreach ($newCategories as $entry) {
            $parent = $this->upsertCategory(
                $entry['name'],
                $entry['slug'],
                $entry['icon'] ?? null,
                null,
                0,
                $hasIconColumn,
                $hasImageColumn
            );
            $keepIds[] = $parent->id;

            foreach ($entry['children'] as $j => $childName) {
                $child = $this->upsertCategory(
                    $childName,
                    $this->generateSlug($childName),
                    null,
                    $parent,
                    $j,
                    $hasIconColumn,
                    $hasImageColumn
                );
                $keepIds[] = $child->id;
            }
        }

        // Process kategori lama yang harus dipertahankan
        foreach ($keepCategories as $entry) {
            $parent = $this->upsertCategory(
                $entry['name'],
                $entry['slug'],
                $entry['icon'] ?? null,
                null,
                0,
                $hasIconColumn,
                $hasImageColumn
            );
            $keepIds[] = $parent->id;

            foreach ($entry['children'] as $j => $childName) {
                $child = $this->upsertCategory(
                    $childName,
                    $this->generateSlug($childName),
                    null,
                    $parent,
                    $j,
                    $hasIconColumn,
                    $hasImageColumn
                );
                $keepIds[] = $child->id;
            }
        }

        // Fallback category untuk produk yang dihapus
        $fallbackCategoryId = Category::query()->where('slug', 'top-up-game')->value('id');
        
        // Hapus kategori lama yang tidak ada di $keepIds
        $obsoleteCategoryIds = Category::query()->whereNotIn('id', $keepIds)->pluck('id');

        // Update produk dari kategori yang dihapus ke fallback category
        if ($fallbackCategoryId && $obsoleteCategoryIds->isNotEmpty() && Schema::hasTable('products')) {
            Product::query()
                ->whereIn('category_id', $obsoleteCategoryIds)
                ->update(['category_id' => $fallbackCategoryId]);
        }

        // Hapus kategori yang obsolete
        if ($obsoleteCategoryIds->isNotEmpty()) {
            Category::query()->whereIn('id', $obsoleteCategoryIds)->delete();
        }
    }

    /**
     * Upsert (update or create) kategori
     */
    private function upsertCategory(
        string $name,
        string $slug,
        ?string $icon,
        ?Category $parent,
        int $sortOrder,
        bool $hasIconColumn,
        bool $hasImageColumn
    ): Category {
        $payload = [
            'name' => $name,
            'sort_order' => $sortOrder,
            'is_active' => true,
            'parent_id' => $parent?->id,
        ];

        if ($hasIconColumn && $icon) {
            $payload['icon'] = $icon;
        }

        return Category::updateOrCreate(
            ['slug' => $slug],
            $payload
        );
    }

    /**
     * Generate slug dari nama
     */
    private function generateSlug(string $name): string
    {
        return strtolower(
            preg_replace(
                '/[^a-z0-9]+/',
                '-',
                preg_replace('/[^\w\s-]/', '', $name)
            )
        );
    }
}

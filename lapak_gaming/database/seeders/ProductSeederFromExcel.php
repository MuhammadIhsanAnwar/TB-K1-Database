<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeederFromExcel extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori sudah ada sebelum seeding produk
        $this->command->info('Memastikan kategori sudah tersedia...');
        $this->call(CategorySeeder::class);

        // Path ke file CSV di folder seeder yang sama
        $csvFile = __DIR__ . '/Data Scrape.csv';

        // Validasi file CSV ada
        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan: {$csvFile}");
            return;
        }

        // Baca dan parse CSV
        $products = $this->parseCSV($csvFile);

        $sellerId = $this->getSellerId();
        if (! $sellerId) {
            $this->command->error('Tidak ada seller/user yang tersedia untuk seller_id. Tambahkan user seller terlebih dahulu.');
            return;
        }

        $this->command->info("Total produk yang akan di-seed: " . count($products));

        // Proses setiap produk
        foreach ($products as $index => $data) {
            try {
                // Cari kategori berdasarkan subkategori dan kategori induknya
                $category = $this->findCategory($data);

                if (!$category) {
                    $this->command->warn("Kategori tidak ditemukan untuk: {$data['kategori']} > {$data['sub_kategori']}");
                    continue;
                }

                // Bersihkan harga (hapus "Rp" dan karakter non-angka)
                $price = $this->parsePrice($data['harga']);

                $slug = $this->generateSlug($data['nama_produk']) . '-' . ($index + 1);

                Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'seller_id' => $sellerId,
                        'category_id' => $category->id,
                        'name' => $data['nama_produk'],
                        'slug' => $slug,
                        'description' => $data['deskripsi'] ?: ('Deskripsi singkat untuk ' . $data['nama_produk']),
                        'price' => $price,
                        'sale_price' => null,
                        'stock' => 10,
                        'type' => 'item',
                        'file_path' => $data['foto_produk'] ?? null,
                        'delivery_content' => null,
                        'is_auto_delivery' => true,
                        'is_featured' => false,
                        'is_trending' => false,
                        'status' => 'published',
                    ]
                );

                if (($index + 1) % 50 == 0) {
                    $this->command->info('Progress: ' . ($index + 1) . ' produk telah di-seed');
                }
            } catch (\Exception $e) {
                $this->command->error("Error pada baris " . ($index + 1) . ": " . $e->getMessage());
            }
        }

        $this->command->info("✅ Seeding produk selesai!");
    }

    /**
     * Parse file CSV dan return array of products
     */
    private function parseCSV(string $filePath): array
    {
        $products = [];
        $handle = fopen($filePath, 'r');

        if ($handle) {
            // Skip header row
            fgetcsv($handle, 0, ';');

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                // Skip baris kosong
                if (empty($row[0])) {
                    continue;
                }

                $products[] = [
                    'kategori' => trim($row[0] ?? ''),
                    'sub_kategori' => trim($row[1] ?? ''),
                    'harga' => trim($row[2] ?? ''),
                    'jumlah_terjual' => trim($row[3] ?? ''),
                    'nama_produk' => trim($row[4] ?? ''),
                    'foto_produk' => trim($row[5] ?? ''),
                    'deskripsi' => trim($row[6] ?? ''),
                ];
            }

            fclose($handle);
        }

        return $products;
    }

    /**
     * Parse harga "Rp28.360" menjadi integer 28360
     */
    private function parsePrice(string $price): float
    {
        // Hapus "Rp" dan whitespace
        $cleaned = str_replace(['Rp', ' ', '.'], '', $price);
        // Konversi ke float dengan 2 desimal
        return (float) $cleaned;
    }

    /**
     * Parse jumlah terjual ke integer
     */
    private function parseSoldCount(?string $count): int
    {
        if (empty($count)) {
            return 0;
        }

        $cleaned = str_replace([' ', '.'], '', trim($count));
        return (int) $cleaned;
    }

    /**
     * Generate slug dari text
     */
    private function generateSlug(string $text): string
    {
        return strtolower(
            preg_replace(
                '/[^a-z0-9]+/',
                '-',
                preg_replace('/[^\w\s-]/', '', $text)
            )
        );
    }

    private function findCategory(array $data): ?Category
    {
        $parentName = $this->normalizeParentCategoryName($data['kategori'] ?? '');
        $subName = trim($data['sub_kategori'] ?? '');
        $parentSlugCandidates = $this->getParentSlugCandidates($parentName);
        $subSlug = $this->generateSlug($subName);

        if ($subSlug !== '') {
            // Cari kategori berdasarkan slug dan parent
            $category = Category::query()
                ->where(function ($query) use ($parentSlugCandidates, $subSlug) {
                    $childSlugCandidates = array_map(
                        fn(string $parentSlug) => $parentSlug . '-' . $subSlug,
                        $parentSlugCandidates
                    );
                    $query->whereIn('slug', array_merge($childSlugCandidates, [$subSlug]));
                })
                ->first();

            if ($category) {
                return $category;
            }

            // Cari kategori berdasarkan parent dan nama (case-insensitive)
            $parent = $this->findParentCategory($parentName);
            if ($parent) {
                $category = Category::query()
                    ->where('parent_id', $parent->id)
                    ->whereRaw('LOWER(name) = ?', [strtolower($subName)])
                    ->first();

                if ($category) {
                    return $category;
                }

                // Jika belum ada, buat kategori baru
                $newSlug = $parent->slug . '-' . $subSlug;
                return Category::firstOrCreate(
                    ['slug' => $newSlug],
                    [
                        'name' => $subName,
                        'parent_id' => $parent->id,
                        'sort_order' => 0,
                        'is_active' => true,
                    ]
                );
            }
        }

        return $this->findParentCategory($parentName);
    }

    private function normalizeParentCategoryName(string $name): string
    {
        $raw = strtolower(trim($name));
        $aliases = [
            'top up' => 'Top Up Game',
            'topup' => 'Top Up Game',
            'top-up' => 'Top Up Game',
            'top up game' => 'Top Up Game',
            'top-up game' => 'Top Up Game',
            'game key' => 'Game Key',
            'voucher' => 'Voucher',
            'pulsa' => 'Pulsa & Utilitas',
            'pulsa utilitas' => 'Pulsa & Utilitas',
            'pulsa & utilitas' => 'Pulsa & Utilitas',
            'aplikasi software' => 'Aplikasi & Software',
            'aplikasi & software' => 'Aplikasi & Software',
            'aplikasi dan software' => 'Aplikasi & Software',
            'item' => 'Item',
            'joki' => 'Joki',
            'streaming' => 'Streaming',
            'live show' => 'Live Show',
            'koin game' => 'Koin Game',
            'top up login' => 'Top Up Login',
            'roblox games' => 'Roblox Games',
        ];

        return $aliases[$raw] ?? trim($name);
    }

    private function findParentCategory(string $name): ?Category
    {
        $slugCandidates = $this->getParentSlugCandidates($name);

        foreach ($slugCandidates as $candidate) {
            $category = Category::query()->where('slug', $candidate)->first();
            if ($category) {
                return $category;
            }
        }

        if ($name !== '') {
            $category = Category::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->first();
            if ($category) {
                return $category;
            }
        }

        foreach ($slugCandidates as $candidate) {
            $fallbackName = match ($candidate) {
                'top-up-game' => 'Top Up Game',
                'pulsa-utilitas' => 'Pulsa & Utilitas',
                'aplikasi-software' => 'Aplikasi & Software',
                'game-key' => 'Game Key',
                'top-up-login' => 'Top Up Login',
                'roblox-games' => 'Roblox Games',
                'koin-game' => 'Koin Game',
                default => $name,
            };

            if ($fallbackName !== '') {
                return Category::firstOrCreate(
                    ['slug' => $candidate],
                    [
                        'name' => $fallbackName,
                        'sort_order' => 0,
                        'is_active' => true,
                    ]
                );
            }
        }

        return null;
    }

    private function getParentSlugCandidates(string $parentName): array
    {
        $parentName = trim($parentName);
        $candidates = [];

        if ($parentName !== '') {
            $candidates[] = $this->generateSlug($parentName);
        }

        $lowerName = strtolower($parentName);

        if (str_contains($lowerName, 'top up') && !str_contains($lowerName, 'game')) {
            $candidates[] = $this->generateSlug('Top Up Game');
        }

        if (str_contains($lowerName, 'pulsa') && !str_contains($lowerName, 'utilitas')) {
            $candidates[] = $this->generateSlug('Pulsa & Utilitas');
        }

        if (str_contains($lowerName, 'software') || str_contains($lowerName, 'aplikasi')) {
            $candidates[] = $this->generateSlug('Aplikasi & Software');
        }

        if (str_contains($lowerName, 'game key') && !str_contains($lowerName, 'game-key')) {
            $candidates[] = $this->generateSlug('Game Key');
        }

        return array_values(array_unique(array_filter($candidates)));
    }

    private function getSellerId(): ?int
    {
        return User::query()->where('role', 'seller')->value('id')
            ?? User::query()->value('id');
    }
}

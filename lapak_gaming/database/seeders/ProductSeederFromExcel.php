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
                        'description' => null,
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
        $parentName = trim($data['kategori'] ?? '');
        $subName = trim($data['sub_kategori'] ?? '');
        $parentSlugCandidates = $this->getParentSlugCandidates($parentName);
        $subSlug = $this->generateSlug($subName);

        if ($subSlug !== '') {
            $childSlugCandidates = array_map(
                fn(string $parentSlug) => $parentSlug . '-' . $subSlug,
                $parentSlugCandidates
            );

            $childSlugCandidates[] = $subSlug;
            $childSlugCandidates = array_unique(array_filter($childSlugCandidates));

            $category = Category::query()
                ->whereIn('slug', $childSlugCandidates)
                ->first();

            if ($category) {
                return $category;
            }

            $category = Category::query()
                ->whereHas('parent', fn($query) => $query->whereIn('slug', $parentSlugCandidates))
                ->whereRaw('LOWER(name) = ?', [strtolower($subName)])
                ->first();

            if ($category) {
                return $category;
            }
        }

        foreach ($parentSlugCandidates as $candidate) {
            $category = Category::query()->where('slug', $candidate)->first();
            if ($category) {
                return $category;
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
            $candidates[] = $this->generateSlug($parentName . ' Game');
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

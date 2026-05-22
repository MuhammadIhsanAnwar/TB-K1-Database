<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductSeederFromExcel extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV di folder seeder yang sama
        $csvFile = __DIR__ . '/Data Scrape.csv';

        // Validasi file CSV ada
        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan: {$csvFile}");
            return;
        }

        // Baca dan parse CSV
        $products = $this->parseCSV($csvFile);

        $this->command->info("Total produk yang akan di-seed: " . count($products));

        // Proses setiap produk
        foreach ($products as $index => $data) {
            try {
                // Cari kategori berdasarkan slug
                $category = Category::where('slug', $this->generateSlug($data['sub_kategori']))
                    ->orWhere('slug', $this->generateSlug($data['kategori']))
                    ->first();

                if (!$category) {
                    $this->command->warn("Kategori tidak ditemukan untuk: {$data['kategori']} > {$data['sub_kategori']}");
                    continue;
                }

                // Bersihkan harga (hapus "Rp" dan karakter non-angka)
                $price = $this->parsePrice($data['harga']);

                // Upsert produk berdasarkan nama
                Product::updateOrCreate(
                    ['name' => $data['nama_produk']],
                    [
                        'category_id' => $category->id,
                        'price' => $price,
                        'image' => $data['foto_produk'] ?? null,
                        'sold_count' => $this->parseSoldCount($data['jumlah_terjual']),
                        'description' => null,
                        'is_active' => true,
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
    private function parsePrice(string $price): int
    {
        // Hapus "Rp" dan whitespace
        $cleaned = str_replace(['Rp', ' ', '.'], '', $price);
        // Konversi ke integer
        return (int) $cleaned;
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
}

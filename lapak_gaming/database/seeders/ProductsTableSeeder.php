<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Support\MarketplaceCategoryCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    private const PRODUCTS_PER_TOP_CATEGORY = 500;

    public function run(): void
    {
        $categoryMap = Category::query()
            ->pluck('id', 'slug')
            ->all();

        if ($categoryMap === []) {
            $this->command->warn('No categories found. Run CategorySeeder first.');
            return;
        }

        $sellerIds = User::query()
            ->where('role', 'seller')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        if ($sellerIds === []) {
            $this->command->warn('No sellers found. Run SellersTableSeeder first.');
            return;
        }

        $columns = array_flip(Schema::getColumnListing('products'));
        $totalProducts = count(MarketplaceCategoryCatalog::tree()) * self::PRODUCTS_PER_TOP_CATEGORY;
        $progressBar = $this->command->getOutput()->createProgressBar($totalProducts);

        $globalIndex = 0;
        foreach (MarketplaceCategoryCatalog::tree() as $parentIndex => $parent) {
            $children = $parent['children'];
            $childCount = count($children);

            if ($childCount === 0) {
                continue;
            }

            $baseCount = intdiv(self::PRODUCTS_PER_TOP_CATEGORY, $childCount);
            $remainder = self::PRODUCTS_PER_TOP_CATEGORY % $childCount;

            foreach ($children as $childIndex => $child) {
                $categoryId = $categoryMap[$child['slug']] ?? null;
                if (! $categoryId) {
                    $this->command->warn("Category slug not found: {$child['slug']}");
                    continue;
                }

                $countForChild = $baseCount + ($childIndex < $remainder ? 1 : 0);
                $imageUrls = $this->imageUrlsForCategory($parent['slug']);

                for ($itemIndex = 1; $itemIndex <= $countForChild; $itemIndex++) {
                    $globalIndex++;
                    $sellerId = $sellerIds[($globalIndex - 1) % count($sellerIds)];
                    $name = $this->buildProductName($parent['slug'], $child['name'], $itemIndex);
                    $slug = Str::slug($name) . '-' . sprintf('%04d', $globalIndex);
                    $price = $this->buildPrice($parent['slug']);
                    $salePrice = $this->buildSalePrice($price);
                    $stock = $this->buildStock($parent['slug']);
                    $imageUrl = $imageUrls[($globalIndex - 1) % count($imageUrls)];

                    $payload = [
                        'seller_id' => $sellerId,
                        'category_id' => $categoryId,
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $this->buildDescription($parent['slug'], $child['name'], $name),
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock' => $stock,
                        'delivery_content' => $this->deliveryContentForCategory($parent['slug']),
                        'is_auto_delivery' => $this->isAutoDeliveryForCategory($parent['slug']),
                        'is_featured' => $itemIndex % 12 === 0,
                        'is_trending' => $itemIndex % 7 === 0,
                        'type' => $this->productTypeForCategory($parent['slug']),
                        'status' => 'published',
                    ];

                    if (isset($columns['file_path'])) {
                        $payload['file_path'] = $imageUrl;
                    } elseif (isset($columns['image'])) {
                        $payload['image'] = $imageUrl;
                    }

                    $payload = array_intersect_key($payload, $columns);

                    Product::updateOrCreate([
                        'slug' => $slug,
                    ], $payload);

                    $progressBar->advance();
                }
            }
        }

        $progressBar->finish();
        $this->command->info("\nSeeded {$globalIndex} products across " . count(MarketplaceCategoryCatalog::tree()) . " top-level categories.");
    }

    private function buildProductName(string $parentSlug, string $childName, int $sequence): string
    {
        $series = sprintf('%03d', $sequence);
        $common = [
            'Instan', 'Promo', 'Hemat', 'Best Seller', 'Langsung', 'Original', 'Premium', 'Official', 'Express', 'Terpercaya',
        ];

        $variant = fake()->randomElement($common);

        return match ($parentSlug) {
            'top-up-game' => "Top Up {$childName} {$variant} #{$series}",
            'game-key' => "Game Key {$childName} - Edition #{$series}",
            'roblox-games' => "Roblox {$childName} Pack {$variant} #{$series}",
            'akun' => "Akun {$childName} {$variant} #{$series}",
            'voucher' => "Voucher {$childName} {$variant} #{$series}",
            'koin-game' => "Koin {$childName} {$variant} #{$series}",
            'item' => "Item {$childName} {$variant} #{$series}",
            'joki' => "Joki {$childName} {$variant} #{$series}",
            'top-up-login' => "Top Up Login {$childName} {$variant} #{$series}",
            'streaming' => "Langganan {$childName} {$variant} #{$series}",
            'live-show' => "Live Show {$childName} {$variant} #{$series}",
            'pulsa-utilitas' => "Pulsa {$childName} {$variant} #{$series}",
            'aplikasi-software' => "Lisensi {$childName} {$variant} #{$series}",
            default => "{$childName} {$variant} #{$series}",
        };
    }

    private function buildDescription(string $parentSlug, string $childName, string $name): string
    {
        $base = match ($parentSlug) {
            'top-up-game' => "Produk top-up untuk {$childName} dengan proses cepat, aman, dan harga kompetitif.",
            'game-key' => "Key resmi {$childName} yang langsung dikirim setelah pembayaran dikonfirmasi.",
            'roblox-games' => "Produk Roblox {$childName} dengan stok tinggi dan pengiriman cepat.",
            'akun' => "Akun {$childName} siap pakai dengan keamanan terjamin dan detail lengkap.",
            'voucher' => "Voucher digital {$childName} resmi untuk aktivasi instan.",
            'koin-game' => "Koin dan currency {$childName} untuk mempercepat progres game Anda.",
            'item' => "Item {$childName} berkualitas untuk melengkapi koleksi dan gameplay.",
            'joki' => "Jasa joki {$childName} profesional untuk mencapai target dengan aman.",
            'top-up-login' => "Top up login {$childName} dengan proses verifikasi cepat dan keamanan terjamin.",
            'streaming' => "Langganan {$childName} aktif selama masa berlaku, siap pakai segera.",
            'live-show' => "Kode atau paket {$childName} untuk akses acara live show dan gift premium.",
            'pulsa-utilitas' => "Pulsa dan utilitas {$childName} untuk kebutuhan komunikasi dan layanan digital.",
            'aplikasi-software' => "Lisensi {$childName} resmi untuk aplikasi dan software produktif.",
            default => "Produk digital {$childName} dengan pelayanan cepat dan aman.",
        };

        return "$base \n
            $name tersedia dengan harga terjangkau dan dikirim segera setelah pembayaran diverifikasi.";
    }

    private function buildPrice(string $parentSlug): int
    {
        return match ($parentSlug) {
            'top-up-game' => fake()->numberBetween(12000, 250000),
            'game-key' => fake()->numberBetween(70000, 1400000),
            'roblox-games' => fake()->numberBetween(15000, 220000),
            'akun' => fake()->numberBetween(90000, 600000),
            'voucher' => fake()->numberBetween(25000, 450000),
            'koin-game' => fake()->numberBetween(12000, 180000),
            'item' => fake()->numberBetween(20000, 350000),
            'joki' => fake()->numberBetween(8000, 180000),
            'top-up-login' => fake()->numberBetween(20000, 180000),
            'streaming' => fake()->numberBetween(49000, 350000),
            'live-show' => fake()->numberBetween(50000, 250000),
            'pulsa-utilitas' => fake()->numberBetween(5000, 120000),
            'aplikasi-software' => fake()->numberBetween(80000, 450000),
            default => fake()->numberBetween(15000, 250000),
        };
    }

    private function buildSalePrice(int $price): ?int
    {
        if (fake()->boolean(25)) {
            return max(0, $price - fake()->numberBetween((int) ($price * 0.08), (int) ($price * 0.22)));
        }

        return null;
    }

    private function buildStock(string $parentSlug): int
    {
        return match ($parentSlug) {
            'akun' => fake()->numberBetween(1, 5),
            'joki' => fake()->numberBetween(5, 35),
            'item' => fake()->numberBetween(10, 120),
            'top-up-game', 'koin-game', 'voucher', 'pulsa-utilitas', 'streaming' => fake()->numberBetween(100, 9999),
            default => fake()->numberBetween(25, 500),
        };
    }

    private function deliveryContentForCategory(string $parentSlug): string
    {
        return match ($parentSlug) {
            'top-up-game' => 'Masukkan ID akun atau server, pengiriman dikirim otomatis setelah pembayaran terkonfirmasi.',
            'game-key' => 'Key dikirim otomatis sesaat setelah pembayaran selesai melalui pesan order.',
            'roblox-games' => 'Silakan sertakan username, pengiriman dilakukan otomatis jika memungkinkan.',
            'akun' => 'Akun akan dikirim via pesan order dengan detail login setelah pembayaran diverifikasi.',
            'voucher' => 'Kode voucher dikirim otomatis setelah konfirmasi pembayaran.',
            'koin-game' => 'Masukkan username dan server yang benar sebelum checkout untuk pengiriman otomatis.',
            'item' => 'Item digital dikirim lewat pesan order setelah pembayaran lengkap.',
            'joki' => 'Kirim detail akun dan target rank, pekerjaan dimulai setelah konfirmasi pembayaran.',
            'top-up-login' => 'Data login diperlukan untuk proses top up, pengiriman dilakukan setelah verifikasi.',
            'streaming' => 'Langganan diaktifkan setelah pembayaran, kirim akun atau email jika diperlukan.',
            'live-show' => 'Kode atau paket live show dikirim setelah pembayaran diverifikasi.',
            'pulsa-utilitas' => 'Masukkan nomor tujuan dan nominal sebelum checkout.',
            'aplikasi-software' => 'Lisensi dikirim lewat pesan order setelah pembayaran dikonfirmasi.',
            default => 'Produk digital dikirim segera setelah pembayaran selesai.',
        };
    }

    private function isAutoDeliveryForCategory(string $parentSlug): bool
    {
        return in_array($parentSlug, ['top-up-game', 'voucher', 'koin-game', 'streaming', 'pulsa-utilitas'], true);
    }

    private function productTypeForCategory(string $parentSlug): string
    {
        return match ($parentSlug) {
            'top-up-game', 'top-up-login' => 'topup',
            'game-key' => 'gamekey',
            'akun' => 'akun',
            'voucher' => 'voucher',
            default => 'item',
        };
    }

    private function imageUrlsForCategory(string $parentSlug): array
    {
        $list = [
            'top-up-game' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fgame%2F2025919%2F0equje4n0ubuiav1fvrh22.jpg&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fgame%2F2024109%2Frcks2r0xntbibkh7gf72p.png&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fgame%2F20231116%2Fy4jqgkdbtbd3zfokbvtknb.jpg&w=1200&q=85',
            ],
            'game-key' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F2025101%2Faksdt03imnfuloh7w1bi9.png&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F20251217%2Foxv5369v4qfngzo8a9mkx.png&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F20251217%2Ftm30fhcucge6ufybb1w8k.png&w=1200&q=85',
            ],
            'roblox-games' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Fillustration%2Fitemku%2Froblox%2Fimage-roblox.png&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fplaceholder%2F2026331%2Ful0o7gsyf8bdncxrk5ezrj.webp&w=1200&q=85',
            ],
            'akun' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fplaceholder%2F2026224%2Ftjt98qorebon5xh9msqegp.png&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fgame_category%2F202396%2Fgwoqgq42u4rwxtprhe8oo.svg&w=1200&q=85',
            ],
            'voucher' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Fillustration%2Fitemku%2Froblox%2Fbg-roblox-character.png&w=1200&q=85',
            ],
            'koin-game' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F2025101%2Faksdt03imnfuloh7w1bi9.png&w=1200&q=85',
            ],
            'item' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F20251217%2Ftm30fhcucge6ufybb1w8k.png&w=1200&q=85',
            ],
            'joki' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F20251217%2Foxv5369v4qfngzo8a9mkx.png&w=1200&q=85',
                'https://imgop.itemku.com/?url=https%3A%2F%2Ffiles.itemku.com%2Fillustration%2Fitemku%2Froblox%2Fimage-roblox.png&w=1200&q=85',
            ],
            'top-up-login' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fplaceholder%2F2026331%2Ful0o7gsyf8bdncxrk5ezrj.webp&w=1200&q=85',
            ],
            'streaming' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fplaceholder%2F2026224%2Ftjt98qorebon5xh9msqegp.png&w=1200&q=85',
            ],
            'live-show' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fgame%2F2024109%2Frcks2r0xntbibkh7gf72p.png&w=1200&q=85',
            ],
            'pulsa-utilitas' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fgame%2F20231116%2Fy4jqgkdbtbd3zfokbvtknb.jpg&w=1200&q=85',
            ],
            'aplikasi-software' => [
                'https://imgop.itemku.com/?url=https%3A%2F%2Fd1x91p7vw3vuq8.cloudfront.net%2Fpage_layout_custom_product%2F2025101%2Faksdt03imnfuloh7w1bi9.png&w=1200&q=85',
            ],
        ];

        return $list[$parentSlug] ?? array_merge(...array_values($list));
    }
}

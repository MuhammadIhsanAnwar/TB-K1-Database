<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\ProductStatistic;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ComprehensiveReviewsSeeder extends Seeder
{
    /**
     * Review comments template untuk berbagai rating
     */
    private array $reviewComments = [
        5 => [
            'Produk bagus banget! Langsung bisa digunakan, recommend penjualnya sangat responsif',
            'Top notch! Dijamin puas, kualitas terbaik, transaksi lancar tanpa masalah',
            'Benar-benar worth it, tidak ada yang mengecewakan, mantap lah',
            'Sempurna! Sesuai harapan, penjual profesional, fast response',
            'Amazing! Beli berulang kali di seller ini, konsisten bagus',
            'Kualitas premium, harganya terjangkau, sangat merekomendasikan',
            'Praktis, cepat, dan sesuai deskripsi. Puas!',
            'Ini pembelian terbaik saya, tidak menyesal',
            'Sangat puas dengan produk dan pelayanannya, 5/5',
            'Tidak ada yang salah, semuanya sempurna dari awal sampai akhir',
            'Produk original, seller terpercaya, transaksi aman',
            'Ini adalah investasi terbaik saya bulan ini',
            'Kualitas tidak perlu diragukan, seller sangat membantu',
            'Pengalaman berbelanja yang luar biasa',
            'Terima kasih penjual! Produknya benar-benar kualitas terbaik',
        ],
        4 => [
            'Cukup puas dengan produk, ada beberapa hal minor yang bisa diperbaiki',
            'Bagus sih tapi agak mengecewa di beberapa aspek, overall ok',
            'Produk sesuai tapi delivery agak lama, tapi kualitasnya bagus',
            'Bagus, tapi ada yang kurang sesuai ekspektasi',
            'Puas kok, just minor improvements needed',
            'Lumayan bagus, sesuai dengan harga yang dibayar',
            'Good quality, seller responsif, delivery bisa lebih cepat',
            'Produk oke, packaging bisa lebih baik',
            'Satisfied enough, tidak terlalu banyak keluhan',
            'Decent product, fair price, acceptable service',
            'Dapat yang diharapkan, tidak lebih tidak kurang',
            'Cukup memuaskan untuk harga segini',
            'Kualitas standar, sesuai deskripsi, cukup puas',
            'Produk bagus tapi ada sedikit cacat packaging',
            'Overall nice, seller baik, komunikasi lancar',
        ],
        3 => [
            'Biasa aja sih, tidak istimewa tapi juga tidak mengecewa',
            'Standar, sesuai deskripsi tapi harga agak mahal',
            'Acceptable tapi ada yang tidak sesuai',
            'Produknya ok tapi packaging jelek',
            'Tidak buruk tapi juga tidak wow',
            'Cukup, tapi ada keluhan kecil tentang kualitas',
            'Lumayan tapi ada part yang bermasalah',
            'Mediocre, bisa dicari lebih baik',
            'Sesuai harga, tapi kualitas bisa lebih baik',
            'Produk normal aja, seller responsif tapi lama',
            'Ada beberapa masalah tapi masih teratasi',
            'Decent enough, tidak merekomendasikan khusus',
            'Fair quality untuk harga standar',
            'Dapat produk tapi ada yang tidak sesuai harapan',
            'Lumayan, tapi banyak yang perlu diperbaiki',
        ],
        2 => [
            'Mengecewa, tidak sesuai deskripsi produknya',
            'Kualitas kurang, jauh dari ekspektasi saya',
            'Produknya jelek, uang terbuang',
            'Tidak recommended, banyak masalah',
            'Buruk, seller tidak responsif',
            'Sangat mengecewa, kualitasnya jelek sekali',
            'Tidak worth it sama sekali',
            'Produk bermasalah, seller susah dihubungi',
            'Kecewa berat dengan pembelian ini',
            'Jangan beli, banyak cacat',
            'Produk tidak bagus, packaging jelek, komunikasi buruk',
            'Rugi membeli di sini',
            'Kualitas jauh di bawah standar',
            'Tidak sesuai yang dijanjikan',
            'Very disappointed with this purchase',
        ],
        1 => [
            'Terburuk! Jangan beli di seller ini',
            'Produk sampai dalam kondisi rusak',
            'Uang terbuang, seller tidak profesional',
            'Sangat tidak puas, minta refund saja',
            'Disaster! Tidak akan beli lagi',
            'Produk fake atau tidak asli',
            'Seller penipu, jangan dipercaya',
            'Kualitas paling jelek yang pernah saya beli',
            'Tidak ada yang positif dari pembelian ini',
            'Worst purchase ever',
            'Produk tidak berfungsi sama sekali',
            'Scam! Jangan percaya seller ini',
            'Benar-benar mengecewakan dari awal',
            'Saya sangat menyesal membeli di sini',
            'Tidak ada alasan untuk memberikan rating positif',
        ],
    ];

    public function run(): void
    {
        $this->command->info('🌟 Seeding comprehensive reviews for all products...');

        if (!Schema::hasTable('products') || !Schema::hasTable('users') || !Schema::hasTable('reviews')) {
            $this->command->error('Missing required tables: products, users, or reviews');
            return;
        }

        $products = Product::active()->inStock()->get();
        $buyers = User::where('role', 'buyer')->whereNotNull('email')->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping reviews seeding.');
            return;
        }

        if ($buyers->isEmpty()) {
            $this->command->warn('No buyers found. Skipping reviews seeding.');
            return;
        }

        $this->command->info("Found {$products->count()} products and {$buyers->count()} buyers");

        $progressBar = $this->command->getOutput()->createProgressBar($products->count());
        $progressBar->start();

        $totalReviewsCreated = 0;

        foreach ($products as $product) {
            // Generate 3-8 reviews for each product
            $reviewCount = random_int(3, 8);
            $ratings = [];

            // Get some orders for this product to reference (optional)
            $productOrders = Order::whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->get();

            for ($i = 0; $i < $reviewCount; $i++) {
                // 70% chance for 4-5 stars, 20% for 3 stars, 10% for 1-2 stars (realistic distribution)
                $ratingChance = random_int(1, 100);
                if ($ratingChance <= 70) {
                    $rating = random_int(4, 5);
                } elseif ($ratingChance <= 90) {
                    $rating = 3;
                } else {
                    $rating = random_int(1, 2);
                }

                $ratings[] = $rating;

                $buyer = $buyers->random();
                $comments = $this->reviewComments[$rating];
                $comment = $comments[array_rand($comments)];

                // Use an order if available, otherwise null
                $orderId = null;
                if ($productOrders->isNotEmpty() && random_int(1, 100) <= 80) {
                    $orderId = $productOrders->random()->id;
                }

                try {
                    Review::create([
                        'product_id' => $product->id,
                        'order_id' => $orderId,
                        'user_id' => $buyer->id,
                        'rating' => $rating,
                        'comment' => $comment,
                        'is_public' => true,
                    ]);

                    $totalReviewsCreated++;
                } catch (\Exception $e) {
                    // Skip on error (e.g., duplicate user review)
                    continue;
                }
            }

            // Update product statistics with rating average
            if (!empty($ratings)) {
                $ratingAverage = array_sum($ratings) / count($ratings);

                ProductStatistic::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'rating_average' => round($ratingAverage, 2),
                        'review_count' => count($ratings),
                    ]
                );
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->info("\n\n✅ Comprehensive reviews seeding completed!");
        $this->command->info("   • Products with reviews: {$products->count()}");
        $this->command->info("   • Total reviews created: {$totalReviewsCreated}");
        $this->command->info("   • Average reviews per product: " . round($totalReviewsCreated / $products->count(), 1));
    }
}

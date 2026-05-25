<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderFinancial;
use App\Models\Review;
use App\Models\User;
use App\Models\ProductStatistic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductViewsAndReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding product views, sales, and reviews...');

        // Get all products
        $products = Product::all();
        $buyers = User::where('role', 'buyer')->limit(500)->get();

        if ($products->isEmpty() || $buyers->isEmpty()) {
            $this->command->warn('No products or buyers found. Skipping seeder.');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar($products->count());
        $progressBar->start();

        foreach ($products as $product) {
            $remainingStock = (int) $product->stock;

            if ($remainingStock <= 0) {
                $progressBar->advance();
                continue;
            }

            // Generate realistic view counts (50-5000 views per product)
            $viewsCount = random_int(50, 5000);

            // Generate sales (5-200 units sold, depending on popularity)
            $popularityFactor = random_int(1, 10);
            $soldCount = match(true) {
                $popularityFactor <= 3 => random_int(5, 30),    // Low popularity
                $popularityFactor <= 7 => random_int(30, 100),  // Medium popularity
                default => random_int(100, 200),                 // High popularity
            };

            // Create or update product statistic
            $statistic = ProductStatistic::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'sold_count' => min($soldCount, $remainingStock),
                    'views_count' => $viewsCount,
                    'downloads_count' => random_int(10, $soldCount * 2),
                ]
            );

            // Create orders and reviews for sold items
            $ratings = [];
            $reviewCount = 0;
            $ordersToCreate = min($soldCount, $remainingStock);

            for ($i = 0; $i < $ordersToCreate; $i++) {
                $product->refresh();
                $remainingStock = (int) $product->stock;

                if ($remainingStock <= 0) {
                    break;
                }

                $buyer = $buyers->random();
                $quantity = 1;

                // Create order using actual database schema (no subtotal/fee/escrow/grand_total)
                $subtotal = $product->price;
                $feeAmount = $subtotal * 0.1; // 10% platform fee
                $grandTotal = $subtotal + $feeAmount;

                $order = Order::create([
                    'buyer_id' => $buyer->id,
                    'seller_id' => $product->seller_id,
                    'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . uniqid(random_int(100, 999)),
                    'payment_method' => collect(['wallet', 'bank_transfer', 'credit_card'])->random(),
                    'status' => 'completed',
                    'completed_at' => now()->subDays(random_int(0, 179)),
                ]);

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name_snapshot' => $product->name,
                    'price_snapshot' => $product->price,
                    'quantity' => $quantity,
                    'status' => 'confirmed',
                ]);

                $remainingStock -= $quantity;

                // Create order financial record
                OrderFinancial::create([
                    'order_id' => $order->id,
                    'subtotal' => $subtotal,
                    'fee_amount' => $feeAmount,
                    'escrow_amount' => 0,
                    'grand_total' => $subtotal + $feeAmount,
                ]);

                // 70% of buyers leave reviews
                if (random_int(1, 100) <= 70) {
                    $rating = random_int(3, 5); // Skew towards positive reviews
                    $ratings[] = $rating;
                    $reviewCount++;

                    // Create review
                    $reviewData = [
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'user_id' => $buyer->id,
                        'rating' => $rating,
                        'comment' => $this->generateReviewComment($rating),
                        'is_public' => true,
                    ];

                    if (Schema::hasColumn('reviews', 'seller_id')) {
                        $reviewData['seller_id'] = $product->seller_id;
                    }

                    Review::create($reviewData);
                }
            }

            // Calculate and update rating average
            if (!empty($ratings)) {
                $ratingAverage = array_sum($ratings) / count($ratings);
                $statistic->update([
                    'rating_average' => round($ratingAverage, 2),
                    'review_count' => $reviewCount,
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->info("\n✓ Product views, sales, and reviews seeding completed!");
        $this->command->info("  • Products: {$products->count()}");
        $this->command->info("  • Total orders created: " . Order::count());
        $this->command->info("  • Total reviews created: " . Review::count());
    }

    private function generateReviewComment(int $rating): string
    {
        $positiveReviews = [
            'Sangat memuaskan, rekomendasi banget!',
            'Produk berkualitas tinggi, sesuai deskripsi.',
            'Pengiriman cepat dan barang bagus, terima kasih!',
            'Mantap, seller responsif dan produknya oke.',
            'Wah, kualitasnya melebihi ekspektasi ku.',
            'Ini bargain banget, worth it abis!',
            'Seller profesional, packaging rapi, barang ok.',
            'Cepat banget proses pengiriman, top deh!',
            'Satisfied customer here, akan beli lagi!',
            'Best seller ever, highly recommended!',
        ];

        $neutralReviews = [
            'Produk standar, sesuai harga.',
            'Cukup baik, tidak ada masalah.',
            'Lumayan, seperti di deskripsi.',
            'Biasa aja tapi tidak mengecewakan.',
            'Produk oke, tapi pengiriman agak lambat.',
            'Acceptable, bisa diperbaiki packaging nya.',
            'Produk ok tapi ada kecil kekurangan.',
        ];

        $negativeReviews = [
            'Kurang memuaskan, tapi masih bisa dipakai.',
            'Ada cacat kecil tapi seller ramah.',
            'Produk agak berbeda dari foto.',
            'Kecewa dengan kualitasnya.',
            'Semoga perbaikan untuk order berikutnya.',
        ];

        return match(true) {
            $rating == 5 => collect($positiveReviews)->random(),
            $rating == 4 => collect($positiveReviews)->random(),
            $rating == 3 => collect($neutralReviews)->random(),
            $rating == 2 => collect($negativeReviews)->random(),
            default => 'Produk tidak sesuai harapan.',
        };
    }
}

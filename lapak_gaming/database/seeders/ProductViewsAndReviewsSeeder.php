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

        // Truncate existing orders and reviews cleanly
        Schema::disableForeignKeyConstraints();
        Review::truncate();
        OrderFinancial::truncate();
        OrderItem::truncate();
        Order::truncate();
        Schema::enableForeignKeyConstraints();

        // Get all products and buyers
        $products = Product::all();
        $buyers = User::where('role', 'buyer')->get();

        if ($products->isEmpty() || $buyers->isEmpty()) {
            $this->command->warn('No products or buyers found. Skipping seeder.');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar($products->count());
        $progressBar->start();

        foreach ($products as $product) {
            // Generate realistic view counts (50-2500 views per product)
            $viewsCount = random_int(50, 2500);

            // Generate small number of sales (2-8 units sold) for very fast seeding
            $soldCount = random_int(2, 8);

            // Create or update product statistic
            $statistic = ProductStatistic::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'sold_count' => $soldCount,
                    'views_count' => $viewsCount,
                    'downloads_count' => random_int(1, $soldCount * 2),
                ]
            );

            // Create orders and reviews for sold items
            $ratings = [];
            $reviewCount = 0;

            for ($i = 0; $i < $soldCount; $i++) {
                $buyer = $buyers->random();

                // Create order
                $subtotal = $product->price;
                $feeAmount = $subtotal * 0.05; // 5% fee
                $grandTotal = $subtotal + $feeAmount;

                $order = Order::create([
                    'buyer_id' => $buyer->id,
                    'seller_id' => $product->seller_id,
                    'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . uniqid(random_int(100, 999)),
                    'payment_method' => collect(['wallet', 'bank_transfer'])->random(),
                    'status' => 'completed',
                    'completed_at' => now()->subDays(random_int(0, 90)),
                ]);

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id,
                    'name_snapshot' => $product->name,
                    'price_snapshot' => $product->price,
                    'quantity' => 1,
                    'status' => 'confirmed',
                ]);

                // Create order financial record
                OrderFinancial::create([
                    'order_id' => $order->id,
                    'subtotal' => $subtotal,
                    'fee_amount' => $feeAmount,
                    'escrow_amount' => 0,
                    'grand_total' => $grandTotal,
                ]);

                // 80% chance of leaving a review
                if (random_int(1, 100) <= 80) {
                    $rating = random_int(4, 5); // Game items generally skew highly positive on itemku (4 or 5 stars)
                    $ratings[] = $rating;
                    $reviewCount++;

                    // Create review
                    Review::create([
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'user_id' => $buyer->id,
                        'seller_id' => $product->seller_id,
                        'rating' => $rating,
                        'comment' => $this->generateReviewComment($rating),
                        'is_public' => true,
                    ]);
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
        $this->command->info("\n✓ Product views, sales, and reviews seeding completed successfully!");
    }

    private function generateReviewComment(int $rating): string
    {
        $positiveReviews = [
            'Sangat cepat prosesnya, mantap!',
            'Respon seller cepat dan ramah, recommended!',
            'Legal dan aman. Senang belanja di sini.',
            'Murah banget dibanding toko lain.',
            'Diamond langsung masuk gak pakai lama.',
            'Proses kilat cuma 1 menit langsung selesai!',
            'Seller terpercaya, transaksi super aman.',
            'Mantap lah, langganan terus di toko ini.',
            'Udah berkali-kali beli di sini selalu puas.',
            'Gak sampai 5 menit udah beres, mantul!'
        ];

        return collect($positiveReviews)->random();
    }
}

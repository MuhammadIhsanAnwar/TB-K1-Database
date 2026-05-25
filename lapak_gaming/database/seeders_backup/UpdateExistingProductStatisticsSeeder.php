<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStatistic;
use Illuminate\Database\Seeder;

class UpdateExistingProductStatisticsSeeder extends Seeder
{
    /**
     * Run the seeder to update statistics for existing products only.
     * Use this if you already have products and want to add stats without creating new orders.
     */
    public function run(): void
    {
        $this->command->info('Updating statistics for existing products...');

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping seeder.');
            return;
        }

        $progressBar = $this->command->getOutput()->createProgressBar($products->count());
        $progressBar->start();

        foreach ($products as $product) {
            // Generate realistic statistics without creating actual orders
            $viewsCount = random_int(50, 5000);
            $soldCount = random_int(5, 200);
            $downloadsCount = random_int(10, $soldCount * 3);
            
            // Generate rating (weighted towards 4-5 stars)
            $ratingChance = random_int(1, 100);
            $ratingAverage = match(true) {
                $ratingChance <= 40 => round(random_int(45, 50) / 10, 2),  // 4.5-5.0
                $ratingChance <= 80 => round(random_int(40, 49) / 10, 2),  // 4.0-4.9
                default => round(random_int(30, 39) / 10, 2),              // 3.0-3.9
            };

            $reviewCount = (int)($soldCount * random_int(60, 80) / 100);

            // Create or update product statistic
            ProductStatistic::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'sold_count' => $soldCount,
                    'rating_average' => $ratingAverage,
                    'review_count' => $reviewCount,
                    'views_count' => $viewsCount,
                    'downloads_count' => $downloadsCount,
                ]
            );

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->info("\n✓ Product statistics updated successfully!");
        $this->command->info("  • Products updated: {$products->count()}");
    }
}

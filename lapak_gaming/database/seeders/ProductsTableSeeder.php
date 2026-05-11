<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) return;

        $columns = array_flip(Schema::getColumnListing('products'));
        $existingCount = Product::count();

        // If products already exist, skip (avoid duplicates on re-seed)
        if ($existingCount > 0) {
            $this->command->info("Database already has $existingCount products, skipping...");
            return;
        }

        // For each seller, create one product per category (schema-adaptive for hosting)
        // Use DB::table to bypass SoftDeletes trait
        DB::table('users')->where('role', 'seller')->chunk(100, function ($sellers) use ($categories, $columns) {
            $progressBar = $this->command->getOutput()->createProgressBar(count($sellers) * $categories->count());
            
            foreach ($sellers as $seller) {
                foreach ($categories as $category) {
                    $name = $category->name . ' - ' . fake()->words(3, true);
                    $slug = Str::slug($name) . '-' . Str::random(4);
                    $imageUrl = 'https://picsum.photos/seed/' . urlencode($slug) . '/800/600';

                    $payload = [
                        'seller_id' => $seller->id,
                        'category_id' => $category->id,
                        'name' => $name,
                        'slug' => $slug,
                        'description' => fake()->paragraph(),
                        'price' => fake()->numberBetween(5000, 2000000),
                        'stock' => fake()->numberBetween(1, 999),
                        'sale_price' => null,
                        'is_auto_delivery' => false,
                        'is_featured' => false,
                        'is_trending' => false,
                        'delivery_content' => null,
                        'file_path' => null,
                        'type' => collect(['topup', 'item', 'akun', 'voucher', 'gamekey'])->random(),
                        'status' => 'published',  // ✅ SET STATUS TO PUBLISHED
                    ];

                    // Ensure product has photo on any schema variant.
                    if (isset($columns['image'])) {
                        $payload['image'] = $imageUrl;
                    } elseif (isset($columns['file_path'])) {
                        $payload['file_path'] = $imageUrl;
                    }

                    // Remove keys that do not exist in current DB schema.
                    $payload = array_intersect_key($payload, $columns);

                    Product::create($payload);
                    $progressBar->advance();
                }
            }
            $progressBar->finish();
        });
        
        $this->command->info("\nProducts seeding completed!");
    }
}

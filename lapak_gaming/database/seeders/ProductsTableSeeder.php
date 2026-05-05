<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) return;

        // For each seller, create one product per category
        User::where('role', 'seller')->chunk(100, function ($sellers) use ($categories) {
            foreach ($sellers as $seller) {
                foreach ($categories as $category) {
                    Product::factory()->create([
                        'seller_id' => $seller->id,
                        'category_id' => $category->id,
                        'name' => $category->name . ' - ' . fake()->words(3, true),
                    ]);
                }
            }
        });
    }
}

<?php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->sentence(3);
        $slug = Str::slug($name) . '-' . Str::random(4);

        return [
            'seller_id' => null,
            'category_id' => null,
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(5000, 2000000),
            'sale_price' => null,
            'stock' => fake()->numberBetween(1, 999),
            'file_path' => null,
            'delivery_content' => null,
            'is_auto_delivery' => false,
            'is_featured' => false,
            'is_trending' => false,
            'image' => 'https://picsum.photos/seed/' . urlencode($slug) . '/800/600',
            'type' => 'digital',
            'status' => 'published',
            'meta' => null,
        ];
    }
}

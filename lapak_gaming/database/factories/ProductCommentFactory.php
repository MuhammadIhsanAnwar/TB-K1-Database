<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCommentFactory extends Factory
{
    protected $model = ProductComment::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'parent_comment_id' => null,
            'content' => $this->faker->paragraph(),
            'rating' => $this->faker->randomElement([null, null, null, 4, 5, 5, 5, 4, 4]),
            'is_verified_buyer' => $this->faker->boolean(70),
            'likes_count' => 0,
            'replies_count' => 0,
            'status' => 'approved',
        ];
    }

    public function withRating(): self
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(1, 5),
        ]);
    }

    public function verified(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_verified_buyer' => true,
        ]);
    }

    public function asReply(ProductComment $parentComment): self
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $parentComment->product_id,
            'parent_comment_id' => $parentComment->id,
        ]);
    }

    public function asSellerReply(Product $product): self
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
            'user_id' => $product->seller_id,
        ]);
    }
}

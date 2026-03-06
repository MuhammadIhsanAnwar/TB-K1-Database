<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SellerLevel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create seller levels
        $this->call(SellerLevelSeeder::class);

        // Create categories
        $this->call(CategorySeeder::class);

        // Create users, sellers and products
        $this->call(UserSeeder::class);
    }
}

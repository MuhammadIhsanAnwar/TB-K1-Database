<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SellerLevelSeeder::class,
            CategorySeeder::class,
        ]);

        $admin = User::factory()->admin()->create([
            'name' => 'Marketplace Admin',
            'email' => 'admin@marketplace.test',
        ]);

        $seller = User::factory()->seller()->create([
            'name' => 'Digital Seller',
            'email' => 'seller@marketplace.test',
            'seller_level_id' => 2,
        ]);

        $buyer = User::factory()->create([
            'name' => 'Digital Buyer',
            'email' => 'buyer@marketplace.test',
        ]);

        $this->call(ProductSeeder::class);
    }
}

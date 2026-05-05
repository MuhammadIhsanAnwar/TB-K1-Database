<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call(SellerLevelSeeder::class);

        // bulk seed categories, users (buyers), sellers and products
        $this->call([
            CategorySeeder::class,
            SellersTableSeeder::class,
            UsersTableSeeder::class,
            ProductsTableSeeder::class,
        ]);

        // Admin
        User::create([
            'name' => 'Admin Lapak Geming',
            'email' => 'admin@lapakgeming.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Demo Seller
        $seller = User::create([
            'name' => 'Seller Demo',
            'email' => 'seller@lapakgeming.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
        ]);

        $sellerWallet = Wallet::create(['user_id' => $seller->id]);
        $sellerWallet->balanceState()->create([
            'balance' => 500000,
            'available_balance' => 500000,
            'locked_balance' => 0,
        ]);

        // Demo Buyer
        $buyer = User::create([
            'name' => 'User Demo',
            'email' => 'user@lapakgeming.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
        ]);

        $buyerWallet = Wallet::create(['user_id' => $buyer->id]);
        $buyerWallet->balanceState()->create([
            'balance' => 200000,
            'available_balance' => 200000,
            'locked_balance' => 0,
        ]);

        // Product/category bulk data is handled by:
        // CategorySeeder, SellersTableSeeder, UsersTableSeeder, ProductsTableSeeder.
    }
}
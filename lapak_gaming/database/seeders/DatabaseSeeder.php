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

        // Admin (only create if not exists)
        $admin = User::firstOrCreate(
            ['email' => 'admin@lapakgeming.com'],
            [
                'name' => 'Admin Lapak Geming',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Demo Seller (only create if not exists)
        $seller = User::firstOrCreate(
            ['email' => 'seller@lapakgeming.com'],
            [
                'name' => 'Seller Demo',
                'password' => Hash::make('password123'),
                'role' => 'seller',
            ]
        );

        // Create wallet for seller if not exists
        $sellerWallet = $seller->wallet ?? Wallet::create(['user_id' => $seller->id]);
        if (!$sellerWallet->balanceState) {
            $sellerWallet->balanceState()->create([
                'balance' => 500000,
                'available_balance' => 500000,
                'locked_balance' => 0,
            ]);
        }

        // Demo Buyer (only create if not exists)
        $buyer = User::firstOrCreate(
            ['email' => 'user@lapakgeming.com'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('password123'),
                'role' => 'buyer',
            ]
        );

        // Create wallet for buyer if not exists
        $buyerWallet = $buyer->wallet ?? Wallet::create(['user_id' => $buyer->id]);
        if (!$buyerWallet->balanceState) {
            $buyerWallet->balanceState()->create([
                'balance' => 200000,
                'available_balance' => 200000,
                'locked_balance' => 0,
            ]);
        }

        // Product/category bulk data is handled by:
        // CategorySeeder, SellersTableSeeder, UsersTableSeeder, ProductsTableSeeder.
    }
}
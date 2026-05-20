<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->command->info('Starting database cleaning and seeding...');

        // 1. Safe Truncation of all tables to ensure a perfectly clean slate
        Schema::disableForeignKeyConstraints();
        DB::table('wallet_transactions')->truncate();
        DB::table('wallet_balances')->truncate();
        DB::table('wallets')->truncate();
        DB::table('seller_level_benefits')->truncate();
        DB::table('seller_levels')->truncate();
        DB::table('user_profiles')->truncate();
        DB::table('product_statistics')->truncate();
        DB::table('reviews')->truncate();
        DB::table('order_financials')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('Database cleaned successfully! Starting seeders...');

        // 2. Call basic seeders in order
        $this->call(SellerLevelSeeder::class);

        $this->call([
            CategorySeeder::class,
            SellersTableSeeder::class,
            UsersTableSeeder::class,
            BuyerSeeder::class,
            ProfilesTableSeeder::class,
            ProductsTableSeeder::class,
        ]);

        if (app()->environment(['local', 'testing'])) {
            $this->call(ProductViewsAndReviewsSeeder::class);
        }

        // 3. Create Default Accounts (Admin, Demo Seller, Demo Buyer)
        $this->command->info('Creating default system accounts...');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'administrator@lapakgaming.neoverse.my.id'],
            [
                'name' => 'Admin Lapak Gaming',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Demo Seller
        $seller = User::firstOrCreate(
            ['email' => 'seller@lapakgeming.com'],
            [
                'name' => 'Seller Demo',
                'password' => Hash::make('password123'),
                'role' => 'seller',
                'status' => 'active',
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

        // Demo Buyer
        $buyer = User::firstOrCreate(
            ['email' => 'user@lapakgeming.com'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('password123'),
                'role' => 'buyer',
                'status' => 'active',
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

        $this->command->info('All seeding tasks completed successfully!');
    }
}
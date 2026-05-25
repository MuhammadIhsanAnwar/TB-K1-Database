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
            BuyerSeeder::class,
            ProfilesTableSeeder::class,
            ProductSeederFromExcel::class,
            AdminUserSeeder::class,
        ]);

        if (app()->environment(['local', 'testing'])) {
            $this->call([
                ProductViewsAndReviewsSeeder::class,
                ComprehensiveReviewsSeeder::class,
            ]);
        }

        // Demo Seller (only create if not exists)
        $seller = User::firstOrCreate(
            ['email' => 'seller@lapakgeming.com'],
            [
                'name' => 'Seller Demo',
                'password' => Hash::make('password123'),
                'role' => 'seller',
                'seller_status' => 'approved',
                'shop_name' => 'Seller Demo Store',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode('Seller Demo Store') . '&background=111827&color=ffffff&bold=true&rounded=true',
                'shop_photo' => 'https://ui-avatars.com/api/?name=' . urlencode('Seller Demo Store') . '&background=111827&color=ffffff&bold=true&rounded=true',
            ]
        );

        if (! $seller->avatar) {
            $seller->forceFill([
                'seller_status' => 'approved',
                'shop_name' => $seller->shop_name ?: 'Seller Demo Store',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($seller->shop_name ?: $seller->name) . '&background=111827&color=ffffff&bold=true&rounded=true',
                'shop_photo' => 'https://ui-avatars.com/api/?name=' . urlencode($seller->shop_name ?: $seller->name) . '&background=111827&color=ffffff&bold=true&rounded=true',
            ])->save();
        }

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
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode('User Demo') . '&background=1f2937&color=ffffff&bold=true&rounded=true',
            ]
        );

        if (! $buyer->avatar) {
            $buyer->forceFill([
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($buyer->name) . '&background=1f2937&color=ffffff&bold=true&rounded=true',
            ])->save();
        }

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
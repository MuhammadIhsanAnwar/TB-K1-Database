<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\SellerAccount;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin Lapak Gaming',
            'email' => 'admin@lapakgaming.local',
            'password' => Hash::make('password'),
            'phone' => '082123456789',
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        Wallet::create([
            'user_id' => $admin->id,
            'balance' => 1000000,
            'hold_balance' => 0,
        ]);

        // Create test buyer users
        for ($i = 1; $i <= 5; $i++) {
            $buyer = User::create([
                'name' => "Buyer Test $i",
                'email' => "buyer$i@test.local",
                'password' => Hash::make('password'),
                'phone' => '082' . str_pad($i, 9, '0', STR_PAD_LEFT),
                'role' => 'buyer',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            Wallet::create([
                'user_id' => $buyer->id,
                'balance' => rand(100000, 5000000),
                'hold_balance' => 0,
            ]);
        }

        // Create test seller users with products
        $products_data = [
            [
                'name' => 'Mobile Legends Diamond 100',
                'price' => 100000,
                'category_id' => 2,
                'description' => 'Diamond Mobile Legends 100 asli langsung masuk akun Anda',
                'stock' => 50,
                'requirements' => 'Akun Mobile Legends level minimal 8',
                'delivery_method' => 'Email',
            ],
            [
                'name' => 'PUBG UC 60',
                'price' => 50000,
                'category_id' => 3,
                'description' => 'UC PUBG Mobile 60 original guaranteed',
                'stock' => 100,
                'requirements' => 'Akun PUBG Mobile aktif',
                'delivery_method' => 'WhatsApp',
            ],
            [
                'name' => 'Premium Game Bundle',
                'price' => 250000,
                'category_id' => 1,
                'description' => 'Bundle 5 game premium dengan license',
                'stock' => 20,
                'requirements' => 'PC dengan spesifikasi menengah',
                'delivery_method' => 'Download Link',
            ],
            [
                'name' => 'Netflix Premium 1 Month',
                'price' => 85000,
                'category_id' => 7,
                'description' => '1 Bulan akses Netflix Premium',
                'stock' => 999,
                'requirements' => 'Email aktif untuk verifikasi',
                'delivery_method' => 'Email',
            ],
            [
                'name' => 'Aged Gaming Account Level 50',
                'price' => 150000,
                'category_id' => 9,
                'description' => 'Akun game dengan karakter level 50 + equipment',
                'stock' => 10,
                'requirements' => 'Bisa langsung main atau dijual kembali',
                'delivery_method' => 'Direct Access',
            ],
            [
                'name' => 'SEO Consultation Package',
                'price' => 500000,
                'category_id' => 11,
                'description' => '1 jam konsultasi SEO untuk website Anda',
                'stock' => 30,
                'requirements' => 'Telepon / Video Call',
                'delivery_method' => 'Appointment',
            ],
        ];

        for ($i = 1; $i <= 3; $i++) {
            $seller = User::create([
                'name' => "Seller Professional $i",
                'email' => "seller$i@test.local",
                'password' => Hash::make('password'),
                'phone' => '082' . str_pad(100 + $i, 9, '0', STR_PAD_LEFT),
                'role' => 'seller',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            // Create wallet
            Wallet::create([
                'user_id' => $seller->id,
                'balance' => rand(500000, 10000000),
                'hold_balance' => 0,
            ]);

            // Create seller account
            SellerAccount::create([
                'user_id' => $seller->id,
                'shop_name' => "Toko Online Seller $i",
                'shop_description' => "Toko jualan digital terpercaya untuk game items, voucher, dan akun. Penjual berpengalaman dengan rating tinggi.",
                'shop_avatar' => 'https://ui-avatars.com/api/?name=Seller' . $i,
                'address' => 'Jalan Test ' . $i,
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'seller_level_id' => ($i == 1) ? 3 : (($i == 2) ? 2 : 1),
                'total_sales' => rand(0, 500),
                'rating' => rand(40, 50) / 10,
                'total_reviews' => rand(0, 100),
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            // Create products for this seller
            shuffle($products_data);
            for ($j = 0; $j < 2; $j++) {
                $product = $products_data[$j];
                Product::create([
                    'seller_id' => $seller->id,
                    'category_id' => $product['category_id'],
                    'name' => $product['name'] . ' (Seller ' . $i . ')',
                    'slug' => Str::slug($product['name'] . ' seller ' . $i),
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'thumbnail' => 'https://via.placeholder.com/300x300?text=' . urlencode($product['name']),
                    'stock' => $product['stock'],
                    'sold' => rand(0, 100),
                    'rating' => rand(40, 50) / 10,
                    'total_reviews' => rand(0, 50),
                    'view_count' => rand(100, 5000),
                    'is_active' => true,
                    'is_featured' => ($i == 1 && $j == 0),
                    'requirements' => $product['requirements'],
                    'delivery_method' => $product['delivery_method'],
                ]);
            }
        }
    }
}

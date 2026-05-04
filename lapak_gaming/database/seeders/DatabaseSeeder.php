<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    public function run(): void {
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
            'balance' => 500000,
        ]);

        // Demo Buyer
        User::create([
            'name' => 'User Demo',
            'email' => 'user@lapakgeming.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'balance' => 200000,
        ]);

        // Kategori
        $games = [
            'Mobile Legends', 'Free Fire', 'PUBG Mobile',
            'Genshin Impact', 'Roblox', 'Valorant', 'Steam',
        ];
        foreach ($games as $game) {
            Category::create([
                'name' => $game,
                'slug' => Str::slug($game),
                'sort_order' => 0,
            ]);
        }

        // Produk contoh
        $categories = Category::all();
        $types = ['topup', 'item', 'akun', 'voucher', 'gamekey'];
        foreach (range(1, 20) as $i) {
            Product::create([
                'user_id'     => $seller->id,
                'category_id' => $categories->random()->id,
                'name'        => 'Produk Game ' . $i,
                'slug'        => 'produk-game-' . $i . '-' . Str::random(4),
                'description' => 'Deskripsi produk game ' . $i,
                'price'       => rand(5, 200) * 1000,
                'stock'       => rand(10, 100),
                'type'        => $types[array_rand($types)],
                'status'      => 'active',
            ]);
        }
    }
}
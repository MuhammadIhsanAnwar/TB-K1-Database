<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductStatistic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SeederController extends Controller
{
    public function itemkuSeeder(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create default admin/seller if not exists
            $seller = User::firstOrCreate(
                ['email' => 'seller@itemku.com'],
                [
                    'name' => 'Itemku Official Store',
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    'store_name' => 'Itemku Official',
                    'store_description' => 'Toko resmi',
                ]
            );

            // Clean up old products
            Product::query()->delete();
            Category::query()->delete();

            // Categories
            $catMobileLegends = Category::create(['name' => 'Mobile Legends', 'slug' => 'mobile-legends', 'is_active' => true, 'icon' => '🎮']);
            $catRoblox = Category::create(['name' => 'Roblox', 'slug' => 'roblox', 'is_active' => true, 'icon' => '🎮']);
            $catFreeFire = Category::create(['name' => 'Free Fire', 'slug' => 'free-fire', 'is_active' => true, 'icon' => '🎮']);
            $catGenshin = Category::create(['name' => 'Genshin Impact', 'slug' => 'genshin-impact', 'is_active' => true, 'icon' => '🎮']);
            $catSteam = Category::create(['name' => 'Steam Wallet', 'slug' => 'steam-wallet', 'is_active' => true, 'icon' => '🎮']);

            $products = [
                // Mobile Legends
                [
                    'category_id' => $catMobileLegends->id,
                    'name' => 'Weekly Diamond Pass Mobile Legends',
                    'type' => 'topup',
                    'price' => 26500,
                    'stock' => 9999,
                    'description' => "Proses kilat 1-5 menit. \nFormat order:\nID Server:\nNickname:",
                    'image' => 'https://placehold.co/400x300/1e4aa3/fff?text=WDP+ML',
                    'sold' => 150420,
                    'rating' => 4.9,
                    'reviews' => 24500
                ],
                [
                    'category_id' => $catMobileLegends->id,
                    'name' => '86 Diamonds Mobile Legends',
                    'type' => 'topup',
                    'price' => 22000,
                    'stock' => 5000,
                    'description' => 'Top up 86 diamond (78 + 8 bonus). Proses cepat via ID & Server.',
                    'image' => 'https://placehold.co/400x300/1e4aa3/fff?text=86+DM+ML',
                    'sold' => 94231,
                    'rating' => 4.8,
                    'reviews' => 12300
                ],
                [
                    'category_id' => $catMobileLegends->id,
                    'name' => 'Joki Mythic Glory MLBB per Bintang',
                    'type' => 'account',
                    'price' => 15000,
                    'stock' => 100,
                    'description' => 'Joki aman, anti ban. Winrate tinggi.',
                    'image' => 'https://placehold.co/400x300/1e4aa3/fff?text=Joki+Glory',
                    'sold' => 4500,
                    'rating' => 5.0,
                    'reviews' => 900
                ],

                // Roblox
                [
                    'category_id' => $catRoblox->id,
                    'name' => '400 Robux - Roblox (100% Legal)',
                    'type' => 'topup',
                    'price' => 75000,
                    'stock' => 1000,
                    'description' => 'Top up Robux legal, masuk langsung. Tuliskan username Roblox kamu.',
                    'image' => 'https://placehold.co/400x300/ea580c/fff?text=400+Robux',
                    'sold' => 67020,
                    'rating' => 4.9,
                    'reviews' => 11000
                ],
                [
                    'category_id' => $catRoblox->id,
                    'name' => 'Blox Fruits - Kitsune Fruit (Permanent)',
                    'type' => 'item',
                    'price' => 450000,
                    'stock' => 50,
                    'description' => 'Pengiriman via gift in-game Blox Fruits.',
                    'image' => 'https://placehold.co/400x300/ea580c/fff?text=Kitsune+Fruit',
                    'sold' => 1250,
                    'rating' => 5.0,
                    'reviews' => 420
                ],
                
                // Free Fire
                [
                    'category_id' => $catFreeFire->id,
                    'name' => '140 Diamonds Free Fire',
                    'type' => 'topup',
                    'price' => 19000,
                    'stock' => 9999,
                    'description' => 'Top up FF via ID saja.',
                    'image' => 'https://placehold.co/400x300/16a34a/fff?text=140+DM+FF',
                    'sold' => 88500,
                    'rating' => 4.8,
                    'reviews' => 9500
                ],

                // Genshin Impact
                [
                    'category_id' => $catGenshin->id,
                    'name' => 'Blessing of the Welkin Moon - Genshin Impact',
                    'type' => 'topup',
                    'price' => 64000,
                    'stock' => 5000,
                    'description' => 'Proses via UID & Server.',
                    'image' => 'https://placehold.co/400x300/60a5fa/fff?text=Welkin+Moon',
                    'sold' => 102000,
                    'rating' => 5.0,
                    'reviews' => 18000
                ],

                // Steam Wallet
                [
                    'category_id' => $catSteam->id,
                    'name' => 'Steam Wallet Code IDR 60.000',
                    'type' => 'key',
                    'price' => 61000,
                    'stock' => 200,
                    'description' => 'Kode dikirim otomatis ke email atau inbox kamu.',
                    'image' => 'https://placehold.co/400x300/000/fff?text=Steam+60k',
                    'sold' => 45300,
                    'rating' => 4.9,
                    'reviews' => 5600
                ],
                [
                    'category_id' => $catSteam->id,
                    'name' => 'Black Myth: Wukong (Steam Gift / Key)',
                    'type' => 'key',
                    'price' => 650000,
                    'stock' => 20,
                    'description' => 'Steam Key Global. Langsung redeem.',
                    'image' => 'https://placehold.co/400x300/000/fff?text=Wukong',
                    'sold' => 840,
                    'rating' => 5.0,
                    'reviews' => 120
                ]
            ];

            foreach ($products as $p) {
                $product = Product::create([
                    'seller_id' => $seller->id,
                    'category_id' => $p['category_id'],
                    'name' => $p['name'],
                    'slug' => Str::slug($p['name']) . '-' . rand(1000, 9999),
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'file_path' => $p['image'],
                    'type' => $p['type'],
                    'status' => 'published',
                    'is_featured' => true,
                ]);

                ProductStatistic::create([
                    'product_id' => $product->id,
                    'sold_count' => $p['sold'],
                    'rating_average' => $p['rating'],
                    'review_count' => $p['reviews'],
                    'views_count' => $p['sold'] * 3,
                ]);
            }

            DB::commit();
            return "BERHASIL SEEDING PRODUK ITEMKU! Total: " . count($products) . " produk.";
        } catch (\Exception $e) {
            DB::rollBack();
            return "ERROR SEEDING: " . $e->getMessage();
        }
    }
}

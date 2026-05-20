<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductStatistic;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get all sellers & categories
        $sellers = User::where('role', 'seller')->get();
        if ($sellers->isEmpty()) {
            $this->command->error("No sellers found! Please run SellersTableSeeder first.");
            return;
        }

        $categories = Category::all()->keyBy('slug');
        if ($categories->isEmpty()) {
            $this->command->error("No categories found! Please run CategorySeeder first.");
            return;
        }

        // 2. Curated realistic products mimicking itemku.com
        $productsData = [
            // Mobile Legends
            [
                'name' => 'Weekly Diamond Pass (WDP) Mobile Legends - Instan',
                'category_slug' => 'mobile-legends',
                'price' => 28000,
                'stock' => 999,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Weekly Diamond Pass resmi & murah. Pengiriman instan via ID & Zone ID. Dapatkan total 210 Diamond + hadiah menarik harian!'
            ],
            [
                'name' => '86 Diamonds Mobile Legends - Top Up Instan',
                'category_slug' => 'mobile-legends-diamond',
                'price' => 19500,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Top Up Diamond Mobile Legends murah dan cepat. 100% legal dan aman. Cukup masukkan ID & Zone ID Game Anda.'
            ],
            [
                'name' => '172 Diamonds Mobile Legends - Top Up Cepat',
                'category_slug' => 'mobile-legends-diamond',
                'price' => 38000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Top Up Diamond Mobile Legends termurah. Proses pengerjaan 1-5 menit saja.'
            ],
            [
                'name' => '344 Diamonds Mobile Legends - Hemat',
                'category_slug' => 'mobile-legends-diamond',
                'price' => 75000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Paket Diamond MLBB hemat untuk event gacha skin impian Anda. Legal & aman terpercaya.'
            ],
            [
                'name' => 'Akun MLBB Mythic Glory Max Emblem - All Hero Unlock',
                'category_slug' => 'mobile-legends-akun',
                'price' => 450000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Dijual Akun MLBB spek dewa. Rank Mythical Glory, Emblem semua MAX, Hero lengkap terbuka semua. Banyak skin epic dan skin special!'
            ],
            [
                'name' => 'Jasa Joki Rank MLBB Epic ke Legend - per Bintang',
                'category_slug' => 'mobile-legends-joki',
                'price' => 4500,
                'stock' => 999,
                'type' => 'item',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Jasa joki rank Mobile Legends profesional & cepat. Dikerjakan oleh player Mythical Immortal. Winrate terjamin aman!'
            ],
            [
                'name' => 'Gift Skin MLBB Lancelot - Royal Matador (Collector)',
                'category_slug' => 'mobile-legends-gift-skin',
                'price' => 280000,
                'stock' => 10,
                'type' => 'item',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
                'description' => 'Sistem Gift Skin resmi MLBB. Diperlukan berteman selama 7 hari di dalam game. 100% aman tanpa resiko banned.'
            ],

            // Roblox
            [
                'name' => '1000 Robux Instan (via Group Funds / Gamepass)',
                'category_slug' => 'roblox',
                'price' => 110000,
                'stock' => 500,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1585647347483-22b66260dfff?q=80&w=800&auto=format&fit=crop',
                'description' => 'Beli Robux murah untuk upgrade avatar Anda. Pengiriman cepat melalui sistem gamepass atau dana grup. 100% legal.'
            ],
            [
                'name' => '400 Robux Murah - Pengiriman Instan',
                'category_slug' => 'roblox-robux',
                'price' => 46000,
                'stock' => 1000,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1585647347483-22b66260dfff?q=80&w=800&auto=format&fit=crop',
                'description' => 'Robux murah untuk kebutuhan harian Anda. Proses instan langsung masuk ke akun Anda.'
            ],
            [
                'name' => 'Blox Fruits - Permanent Buddha Fruit (Instan)',
                'category_slug' => 'roblox-items-pets',
                'price' => 185000,
                'stock' => 50,
                'type' => 'item',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1585647347483-22b66260dfff?q=80&w=800&auto=format&fit=crop',
                'description' => 'Buah permanen Buddha untuk game Blox Fruits Roblox. Sangat direkomendasikan untuk grinding level dan raid boss.'
            ],
            [
                'name' => 'Pet Simulator 99 - 100 Juta Diamonds / Gems',
                'category_slug' => 'roblox-items-pets',
                'price' => 14000,
                'stock' => 999,
                'type' => 'item',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1585647347483-22b66260dfff?q=80&w=800&auto=format&fit=crop',
                'description' => 'Diamonds / Gems Pet Simulator 99 murah untuk membeli pet eksklusif di Plaza Dagang.'
            ],
            [
                'name' => 'Akun Roblox Premium 2019 + Limited Inventory Items',
                'category_slug' => 'roblox-akun',
                'price' => 250000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1585647347483-22b66260dfff?q=80&w=800&auto=format&fit=crop',
                'description' => 'Dijual akun Roblox tahun pembuatan 2019. Sudah ada inventory robux, baju limited, dan level tinggi di beberapa game populer.'
            ],

            // Growtopia
            [
                'name' => '1 Diamond Lock (DL) Growtopia - Pengiriman 3 Menit',
                'category_slug' => 'growtopia',
                'price' => 13500,
                'stock' => 5000,
                'type' => 'item',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?q=80&w=800&auto=format&fit=crop',
                'description' => 'Mata uang Diamond Lock Growtopia legal dan super cepat. Cantumkan nama World dan GrowID Anda serta pastikan World memiliki Donation Box / Display Box.'
            ],
            [
                'name' => '10 Diamond Locks (DL) Growtopia - Grosir Murah',
                'category_slug' => 'growtopia-dl-wl-gems',
                'price' => 130000,
                'stock' => 200,
                'type' => 'item',
                'is_featured' => false,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?q=80&w=800&auto=format&fit=crop',
                'description' => 'Paket grosir 10 Diamond Locks (DL) untuk modal trading atau merintis world impian Anda.'
            ],
            [
                'name' => 'Rayman Fists Growtopia - Item Legendaris',
                'category_slug' => 'growtopia-items',
                'price' => 3400000,
                'stock' => 2,
                'type' => 'item',
                'is_featured' => true,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?q=80&w=800&auto=format&fit=crop',
                'description' => 'Item super langka Rayman Fists Growtopia. Memberikan efek break block jarak jauh. Dijamin keasliannya lewat sistem escrow aman.'
            ],
            [
                'name' => 'Akun Growtopia Level 65 + 100 World Locks',
                'category_slug' => 'growtopia-akun',
                'price' => 195000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?q=80&w=800&auto=format&fit=crop',
                'description' => 'Akun Growtopia siap pakai level 65. Memiliki ring quest lengkap, peninggalan item berharga, dan bonus 100 WL di inventory.'
            ],

            // Free Fire
            [
                'name' => '355 Diamonds Free Fire - Murah Instan',
                'category_slug' => 'free-fire',
                'price' => 43500,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=800&auto=format&fit=crop',
                'description' => 'Top Up FF Murah dan Cepat. Cukup dengan User ID Free Fire Anda, diamond langsung masuk otomatis dalam hitungan detik.'
            ],
            [
                'name' => '720 Diamonds Free Fire - Legal Cepat',
                'category_slug' => 'free-fire-diamond',
                'price' => 87000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=800&auto=format&fit=crop',
                'description' => 'Top Up Diamond FF resmi dari distributor terpercaya. Jaminan aman dari banned.'
            ],
            [
                'name' => 'Akun Free Fire Old Season 2 Elite Pass + Bundle Langka',
                'category_slug' => 'free-fire-akun',
                'price' => 580000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => true,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=800&auto=format&fit=crop',
                'description' => 'Dijual akun old FF koleksi pribadi. Elite Pass Season 2 (Hip Hop bundle), skin senjata legendaris, dan rank Master tier.'
            ],

            // PUBG Mobile
            [
                'name' => '325 UC PUBG Mobile - Proses Otomatis',
                'category_slug' => 'pubg-mobile',
                'price' => 58000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?q=80&w=800&auto=format&fit=crop',
                'description' => 'Beli UC PUBG Mobile resmi. Masukkan Player ID Anda dengan teliti. Proses pengisian instan 24 jam non-stop.'
            ],
            [
                'name' => '660 UC PUBG Mobile - Promo Royal Pass',
                'category_slug' => 'pubg-mobile-uc',
                'price' => 115000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?q=80&w=800&auto=format&fit=crop',
                'description' => 'UC PUBG Mobile murah untuk membeli Royale Pass terbaru Anda. Legal, murah, dan aman.'
            ],
            [
                'name' => 'Akun PUBGM M416 Glacier Level 5 + X-Suit Poseidon',
                'category_slug' => 'pubg-mobile-akun',
                'price' => 1200000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?q=80&w=800&auto=format&fit=crop',
                'description' => 'Dijual akun PUBG Mobile Sultan. Memiliki M416 Glacier Level 5 (efek kill & hit), X-Suit Poseidon Level 3, dan banyak skin kendaraan legendaris.'
            ],

            // Genshin Impact
            [
                'name' => 'Blessing of the Welkin Moon (30 Hari) - Instan',
                'category_slug' => 'genshin-impact',
                'price' => 64000,
                'stock' => 5000,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?q=80&w=800&auto=format&fit=crop',
                'description' => 'Welkin Moon Genshin Impact termurah. Dapatkan total 300 Genesis Crystals instan + 90 Primogems setiap hari selama 30 hari.'
            ],
            [
                'name' => '300 Genesis Crystals Genshin Impact - Murah',
                'category_slug' => 'genshin-impact-top-up',
                'price' => 63000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?q=80&w=800&auto=format&fit=crop',
                'description' => 'Genesis Crystals murah via UID & Server Game Anda. 100% aman dan bebas minus.'
            ],
            [
                'name' => 'Akun Genshin AR 56 - C1 Furina + Sig Weapon + Arlecchino',
                'category_slug' => 'genshin-impact-akun',
                'price' => 380000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?q=80&w=800&auto=format&fit=crop',
                'description' => 'Akun Genshin Impact siap pakai Adventure Rank 56. Karakter Bintang 5 melimpah: Furina C1 dengan senjata signature, Arlecchino, Zhongli, Raiden Shogun, dll. Eksplorasi map rapi.'
            ],

            // Valorant
            [
                'name' => '1000 Valorant Points (VP) Indonesia - Instan',
                'category_slug' => 'valorant',
                'price' => 92000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1553481187-be93c21490a9?q=80&w=800&auto=format&fit=crop',
                'description' => 'Beli Valorant Points (VP) murah untuk membeli skin bundle terbaru atau battle pass. Proses instan via Riot ID.'
            ],
            [
                'name' => '475 Valorant Points (VP) Indonesia - Cepat',
                'category_slug' => 'valorant-points',
                'price' => 45000,
                'stock' => 9999,
                'type' => 'topup',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1553481187-be93c21490a9?q=80&w=800&auto=format&fit=crop',
                'description' => 'Top Up VP murah & cepat 24 jam otomatis.'
            ],
            [
                'name' => 'Akun Valorant Kuronami Vandal + Reaver Sheriff',
                'category_slug' => 'valorant-akun',
                'price' => 295000,
                'stock' => 1,
                'type' => 'akun',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1553481187-be93c21490a9?q=80&w=800&auto=format&fit=crop',
                'description' => 'Dijual akun Valorant server Indo. Tier Platinum 2. Memiliki skin premium Kuronami Vandal (Max upgraded) dan Reaver Sheriff. First hand, aman bergaransi.'
            ],

            // Steam Wallet
            [
                'name' => 'Steam Wallet Code Rp 120.000 (IDR) - Voucher Instan',
                'category_slug' => 'steam-wallet',
                'price' => 132000,
                'stock' => 1000,
                'type' => 'voucher',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?q=80&w=800&auto=format&fit=crop',
                'description' => 'Voucher Steam Wallet Code nominal Rp 120.000 IDR resmi. Masukkan kode voucher di halaman Steam Account Anda untuk menambah saldo secara instan.'
            ],
            [
                'name' => 'Steam Wallet Code Rp 45.000 (IDR)',
                'category_slug' => 'steam-voucher-idr',
                'price' => 50000,
                'stock' => 1000,
                'type' => 'voucher',
                'is_featured' => false,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?q=80&w=800&auto=format&fit=crop',
                'description' => 'Steam Wallet Code nominal Rp 45.000 resmi & cepat.'
            ],
            [
                'name' => 'Steam Wallet Code $10 (USD) Global',
                'category_slug' => 'steam-voucher-usd',
                'price' => 152000,
                'stock' => 500,
                'type' => 'voucher',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1612287230202-1bf1d85d1bdf?q=80&w=800&auto=format&fit=crop',
                'description' => 'Steam Wallet Code nominal 10 USD untuk akun Steam global atau regional tertentu.'
            ],

            // Netflix
            [
                'name' => 'Akun Netflix Premium UHD 1 Bulan - Sharing (1 Profile)',
                'category_slug' => 'netflix',
                'price' => 24500,
                'stock' => 300,
                'type' => 'voucher',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1574375927938-d5a98e8edd86?q=80&w=800&auto=format&fit=crop',
                'description' => 'Akun Netflix Premium Ultra HD 4K durasi 1 Bulan. Akses sharing 1 Profil untuk 1 device. Garansi penuh 30 hari.'
            ],
            [
                'name' => 'Akun Netflix Premium UHD 1 Bulan - Private (5 Profile)',
                'category_slug' => 'netflix-akun-premium',
                'price' => 115000,
                'stock' => 50,
                'type' => 'voucher',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1574375927938-d5a98e8edd86?q=80&w=800&auto=format&fit=crop',
                'description' => 'Akun Netflix Premium Ultra HD 4K Private utuh 1 akun (5 Profil). Bisa ubah password & PIN. Garansi anti-on hold.'
            ],

            // Spotify
            [
                'name' => 'Spotify Premium 1 Bulan - Family Plan Murah',
                'category_slug' => 'spotify',
                'price' => 12500,
                'stock' => 500,
                'type' => 'voucher',
                'is_featured' => true,
                'is_trending' => true,
                'image_url' => 'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?q=80&w=800&auto=format&fit=crop',
                'description' => 'Langganan Spotify Premium durasi 30 hari via undang Family Plan. 100% legal, tanpa iklan, kualitas audio paling jernih.'
            ],
            [
                'name' => 'Spotify Premium 3 Bulan - Individual Plan Premium',
                'category_slug' => 'spotify-premium-plan',
                'price' => 35000,
                'stock' => 100,
                'type' => 'voucher',
                'is_featured' => false,
                'is_trending' => false,
                'image_url' => 'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?q=80&w=800&auto=format&fit=crop',
                'description' => 'Spotify Premium durasi 90 hari plan individual. Akun baru atau akun lama bisa dibantu perpanjang.'
            ]
        ];

        // 3. Insert and set up statistics
        $progressBar = $this->command->getOutput()->createProgressBar(count($productsData));
        $progressBar->start();

        foreach ($productsData as $item) {
            $category = $categories->get($item['category_slug']);
            if (!$category) {
                $this->command->warn("Category slug '{$item['category_slug']}' not found, skipping.");
                $progressBar->advance();
                continue;
            }

            $seller = $sellers->random();
            $slug = Str::slug($item['name']) . '-' . Str::random(4);

            $product = Product::create([
                'seller_id' => $seller->id,
                'category_id' => $category->id,
                'name' => $item['name'],
                'slug' => $slug,
                'description' => $item['description'],
                'price' => $item['price'],
                'sale_price' => null,
                'stock' => $item['stock'],
                'delivery_content' => 'Gunakan detail voucher/akun Anda di area ini untuk pembeli.',
                'is_auto_delivery' => true,
                'is_featured' => $item['is_featured'],
                'is_trending' => $item['is_trending'],
                'file_path' => $item['image_url'],
                'type' => $item['type'],
                'status' => 'published',
            ]);

            // Create statistics to prevent null references on home page
            ProductStatistic::create([
                'product_id' => $product->id,
                'sold_count' => random_int(15, 200),
                'rating_average' => number_format(4.6 + (random_int(0, 4) / 10), 1),
                'review_count' => random_int(8, 50),
                'views_count' => random_int(120, 2500),
                'downloads_count' => random_int(5, 100),
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->info("\nSeeded " . count($productsData) . " premium products successfully!");
    }
}

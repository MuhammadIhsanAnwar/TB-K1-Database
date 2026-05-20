"""
seeder_generator.py - Generate file PHP Laravel Seeder dari hasil scraping

Output: 
  - ItemkuCategoriesSeeder.php  → isi tabel categories
  - ItemkuProductsSeeder.php    → isi tabel products + product_statistics
"""

import os
import re
from datetime import datetime
from slugify import slugify
from logger import get_logger

logger = get_logger("seeder_generator")

OUTPUT_DIR = os.getenv("OUTPUT_DIR", "../database/seeders")


# ─────────────────────────────────────────────
# HELPERS
# ─────────────────────────────────────────────

def php_str(value) -> str:
    """Escape string untuk PHP single-quoted string."""
    if value is None:
        return "null"
    escaped = str(value).replace("\\", "\\\\").replace("'", "\\'")
    return f"'{escaped}'"


def php_num(value, decimals: int = 2) -> str:
    """Format angka untuk PHP."""
    if value is None:
        return "null"
    try:
        if decimals == 0:
            return str(int(value))
        return f"{float(value):.{decimals}f}"
    except (TypeError, ValueError):
        return "0"


def make_slug(name: str, existing: set) -> str:
    """Buat slug unik, tambah suffix angka jika tabrakan."""
    base = slugify(name, allow_unicode=False, max_length=200)
    slug = base
    counter = 1
    while slug in existing:
        slug = f"{base}-{counter}"
        counter += 1
    existing.add(slug)
    return slug


def now_php() -> str:
    return datetime.now().strftime("%Y-%m-%d %H:%M:%S")


def map_product_type(category_name: str) -> str:
    name = category_name.lower()
    if any(k in name for k in ["top up", "topup", "diamond", "coin", "uc", "crystal", "gem"]):
        return "topup"
    if any(k in name for k in ["akun", "account"]):
        return "akun"
    if any(k in name for k in ["voucher", "gift card", "redeem", "wallet"]):
        return "voucher"
    if any(k in name for k in ["key", "cd key", "steam", "game key"]):
        return "gamekey"
    return "item"


# ─────────────────────────────────────────────
# MAPPING KATEGORI
# ─────────────────────────────────────────────

CATEGORY_MAPPING = {
    "mobile-legends": {
        "name": "Mobile Legends", "slug": "mobile-legends",
        "description": "Top up dan item Mobile Legends", "type": "topup",
    },
    "free-fire": {
        "name": "Free Fire", "slug": "free-fire",
        "description": "Diamond dan item Free Fire", "type": "topup",
    },
    "pubg-mobile": {
        "name": "PUBG Mobile", "slug": "pubg-mobile",
        "description": "UC dan item PUBG Mobile", "type": "topup",
    },
    "genshin-impact": {
        "name": "Genshin Impact", "slug": "genshin-impact",
        "description": "Genesis Crystals dan item Genshin Impact", "type": "topup",
    },
    "valorant": {
        "name": "Valorant", "slug": "valorant",
        "description": "VP dan item Valorant", "type": "topup",
    },
    "steam-wallet": {
        "name": "Steam Wallet", "slug": "steam-wallet",
        "description": "Steam Wallet Code", "type": "voucher",
    },
    "honkai-star-rail": {
        "name": "Honkai Star Rail", "slug": "honkai-star-rail",
        "description": "Oneiric Shard dan item Honkai Star Rail", "type": "topup",
    },
    "point-blank": {
        "name": "Point Blank", "slug": "point-blank",
        "description": "Cash dan item Point Blank", "type": "topup",
    },
}


# ─────────────────────────────────────────────
# GENERATE CATEGORIES SEEDER
# ─────────────────────────────────────────────

def generate_categories_seeder(scraped: dict, output_dir: str) -> tuple[str, list[dict]]:
    """
    Generate ItemkuCategoriesSeeder.php
    Return: (filepath, list of category dicts dengan id)
    """
    categories = []
    slug_set = set()
    ts = now_php()

    for i, category_slug in enumerate(scraped.keys(), start=1):
        info = CATEGORY_MAPPING.get(
            category_slug,
            {
                "name": category_slug.replace("-", " ").title(),
                "slug": category_slug,
                "description": "",
                "type": "item",
            }
        )

        slug = make_slug(info["slug"], slug_set)

        categories.append({
            "_id":         i,          # ID sementara untuk referensi antar seeder
            "slug_key":    category_slug,
            "parent_id":   "null",
            "name":        info["name"],
            "slug":        slug,
            "description": info.get("description", ""),
            "type":        info.get("type", "item"),
            "sort_order":  i,
            "is_active":   1,
            "created_at":  ts,
            "updated_at":  ts,
        })

    # Bangun baris PHP array
    rows = []
    for cat in categories:
        rows.append(f"""            [
                'parent_id'   => {cat['parent_id']},
                'name'        => {php_str(cat['name'])},
                'slug'        => {php_str(cat['slug'])},
                'description' => {php_str(cat['description'])},
                'icon'        => null,
                'image'       => null,
                'sort_order'  => {cat['sort_order']},
                'is_active'   => 1,
                'created_at'  => {php_str(cat['created_at'])},
                'updated_at'  => {php_str(cat['updated_at'])},
            ],""")

    rows_str = "\n".join(rows)

    php = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

/**
 * ItemkuCategoriesSeeder
 *
 * Di-generate otomatis oleh Itemku Scraper
 * Tanggal : {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
 * Kategori: {", ".join(scraped.keys())}
 *
 * Cara pakai:
 *   php artisan db:seed --class=ItemkuCategoriesSeeder
 *
 * CATATAN: Seeder ini menggunakan INSERT IGNORE agar tidak error
 *          jika slug sudah ada di database.
 */
class ItemkuCategoriesSeeder extends Seeder
{{
    public function run(): void
    {{
        $categories = [
{rows_str}
        ];

        foreach ($categories as $category) {{
            // Gunakan updateOrInsert agar aman dijalankan berulang kali
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category
            );
        }}

        $this->command->info('✅ Kategori Itemku berhasil di-seed: ' . count($categories) . ' kategori');
    }}
}}
"""

    os.makedirs(output_dir, exist_ok=True)
    filepath = os.path.join(output_dir, "ItemkuCategoriesSeeder.php")
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(php)

    logger.info(f"📁 Seeder kategori: {filepath} ({len(categories)} kategori)")
    return filepath, categories


# ─────────────────────────────────────────────
# GENERATE PRODUCTS SEEDER
# ─────────────────────────────────────────────

def generate_products_seeder(
    scraped: dict,
    categories: list[dict],
    seller_user_id: int,
    output_dir: str,
) -> str:
    """
    Generate ItemkuProductsSeeder.php
    Mengisi tabel products + product_statistics sekaligus.
    """
    # Buat map: slug_key → category info
    cat_map = {cat["slug_key"]: cat for cat in categories}

    ts = now_php()
    slug_set = set()

    product_rows = []
    stats_rows = []
    product_counter = 0

    for category_slug, products in scraped.items():
        if not products:
            continue

        cat = cat_map.get(category_slug, {})
        cat_slug_php = php_str(cat.get("slug", category_slug))
        product_type = cat.get("type", "item")

        for p in products:
            name = (p.get("name") or "").strip()
            if not name:
                continue

            product_counter += 1
            slug = make_slug(slugify(name, allow_unicode=False, max_length=200), slug_set)

            price       = float(p.get("price") or 0)
            sale_price  = p.get("sale_price")
            stock       = int(p.get("stock") or 0)
            description = (p.get("description") or "").strip()[:2000]

            sold_count     = int(p.get("sold_count") or 0)
            rating_average = float(p.get("rating_average") or 0.0)
            review_count   = int(p.get("review_count") or 0)

            # ── Products row
            sale_price_php = php_num(sale_price) if sale_price else "null"

            product_rows.append(f"""
            // #{product_counter} - {name[:60]}
            [
                'category_slug'   => {cat_slug_php},
                'seller_user_id'  => {seller_user_id},
                'name'            => {php_str(name)},
                'slug'            => {php_str(slug)},
                'description'     => {php_str(description)},
                'price'           => {php_num(price)},
                'sale_price'      => {sale_price_php},
                'stock'           => {php_num(stock, 0)},
                'type'            => {php_str(product_type)},
                'is_auto_delivery'=> 1,
                'is_featured'     => 0,
                'is_trending'     => 0,
                'status'          => 'published',
                'views_count'     => 0,
                'created_at'      => {php_str(ts)},
                'updated_at'      => {php_str(ts)},
            ],""")

            # ── Statistics row (pakai slug produk sebagai penghubung)
            stats_rows.append(f"""
            [
                'product_slug'    => {php_str(slug)},
                'sold_count'      => {php_num(sold_count, 0)},
                'rating_average'  => {php_num(rating_average)},
                'review_count'    => {php_num(review_count, 0)},
                'views_count'     => 0,
                'downloads_count' => 0,
                'created_at'      => {php_str(ts)},
                'updated_at'      => {php_str(ts)},
            ],""")

    product_rows_str = "\n".join(product_rows)
    stats_rows_str   = "\n".join(stats_rows)

    php = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

/**
 * ItemkuProductsSeeder
 *
 * Di-generate otomatis oleh Itemku Scraper
 * Tanggal : {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
 * Total   : {product_counter} produk dari {len(scraped)} kategori
 *
 * Cara pakai (jalankan SETELAH ItemkuCategoriesSeeder):
 *   php artisan db:seed --class=ItemkuProductsSeeder
 *
 * Atau lewat DatabaseSeeder:
 *   $this->call([ItemkuCategoriesSeeder::class, ItemkuProductsSeeder::class]);
 */
class ItemkuProductsSeeder extends Seeder
{{
    public function run(): void
    {{
        // ─────────────────────────────────────────────
        // 1. PRODUCTS
        // ─────────────────────────────────────────────
        $products = [{product_rows_str}
        ];

        $insertedCount = 0;
        $updatedCount  = 0;

        foreach ($products as $data) {{
            // Ambil category_id dari slug
            $category = DB::table('categories')
                ->where('slug', $data['category_slug'])
                ->first();

            if (! $category) {{
                $this->command->warn("⚠️  Kategori tidak ditemukan: {{$data['category_slug']}} — jalankan ItemkuCategoriesSeeder dulu!");
                continue;
            }}

            $productData = array_merge(
                array_diff_key($data, array_flip(['category_slug', 'seller_user_id'])),
                [
                    'category_id' => $category->id,
                    'seller_id'   => $data['seller_user_id'],
                ]
            );

            $exists = DB::table('products')
                ->where('seller_id', $data['seller_user_id'])
                ->where('name', $data['name'])
                ->first();

            if ($exists) {{
                // Update: hanya harga, stok, deskripsi
                DB::table('products')->where('id', $exists->id)->update([
                    'price'       => $productData['price'],
                    'sale_price'  => $productData['sale_price'],
                    'stock'       => $productData['stock'],
                    'description' => $productData['description'],
                    'updated_at'  => now(),
                ]);
                $updatedCount++;
            }} else {{
                DB::table('products')->insert($productData);
                $insertedCount++;
            }}
        }}

        $this->command->info("✅ Products: {{$insertedCount}} baru, {{$updatedCount}} diupdate");

        // ─────────────────────────────────────────────
        // 2. PRODUCT STATISTICS
        // ─────────────────────────────────────────────
        $statistics = [{stats_rows_str}
        ];

        $statsCount = 0;
        foreach ($statistics as $stat) {{
            $product = DB::table('products')
                ->where('slug', $stat['product_slug'])
                ->first();

            if (! $product) {{
                continue;
            }}

            $statData = array_merge(
                array_diff_key($stat, array_flip(['product_slug'])),
                ['product_id' => $product->id]
            );

            DB::table('product_statistics')->updateOrInsert(
                ['product_id' => $product->id],
                $statData
            );
            $statsCount++;
        }}

        $this->command->info("✅ Product Statistics: {{$statsCount}} records");
        $this->command->info("🎉 Total produk Itemku berhasil di-seed: {product_counter}");
    }}
}}
"""

    filepath = os.path.join(output_dir, "ItemkuProductsSeeder.php")
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(php)

    logger.info(f"📦 Seeder produk: {filepath} ({product_counter} produk)")
    return filepath


# ─────────────────────────────────────────────
# GENERATE DATABASE SEEDER (ENTRY POINT)
# ─────────────────────────────────────────────

def generate_all(scraped: dict, seller_user_id: int, output_dir: str) -> dict:
    """
    Main function: generate semua seeder dari hasil scraping.
    Return: dict berisi path file yang dihasilkan.
    """
    logger.info(f"\n🖊️  Membuat Laravel Seeder di: {os.path.abspath(output_dir)}")

    cat_filepath, categories = generate_categories_seeder(scraped, output_dir)
    prod_filepath = generate_products_seeder(scraped, categories, seller_user_id, output_dir)

    total_products = sum(len(v) for v in scraped.values())

    return {
        "categories_seeder": cat_filepath,
        "products_seeder":   prod_filepath,
        "total_categories":  len(categories),
        "total_products":    total_products,
    }
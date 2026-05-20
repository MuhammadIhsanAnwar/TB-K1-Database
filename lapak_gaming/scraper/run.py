"""
run.py - Entry point utama
Scraping Itemku → Generate Laravel Seeder PHP

Jalankan: python run.py
"""

import asyncio
import os
import sys
from dotenv import load_dotenv

load_dotenv()

from itemku import ItemkuScraper
from seeder_generator import generate_all
from logger import get_logger

logger = get_logger("run")


async def main():
    logger.info("=" * 60)
    logger.info("🚀 ITEMKU SCRAPER → LARAVEL SEEDER")
    logger.info("=" * 60)

    seller_user_id    = int(os.getenv("SELLER_USER_ID", 1))
    raw_categories    = os.getenv("TARGET_CATEGORIES", "mobile-legends")
    target_categories = [c.strip() for c in raw_categories.split(",") if c.strip()]
    output_dir        = os.getenv("OUTPUT_DIR", "../database/seeders")

    logger.info(f"👤 Seller User ID  : {seller_user_id}")
    logger.info(f"🎯 Kategori target : {', '.join(target_categories)}")
    logger.info(f"📂 Output folder   : {os.path.abspath(output_dir)}")

    # 1. Scraping
    logger.info("\n📡 Memulai scraping...")
    scraper = ItemkuScraper()
    scraped = await scraper.run(target_categories)

    total = sum(len(v) for v in scraped.values())
    logger.info(f"✅ Selesai scraping: {total} produk dari {len(scraped)} kategori")

    if total == 0:
        logger.warning("⚠️  Tidak ada produk. Seeder tidak dibuat.")
        sys.exit(0)

    # 2. Generate seeder
    logger.info("\n🖊️  Membuat file PHP Seeder...")
    result = generate_all(scraped, seller_user_id, output_dir)

    # 3. Ringkasan
    logger.info("\n" + "=" * 60)
    logger.info("✅ SEEDER BERHASIL DIBUAT!")
    logger.info(f"   📁 {result['categories_seeder']}")
    logger.info(f"   📦 {result['products_seeder']}")
    logger.info(f"\n   Kategori : {result['total_categories']}")
    logger.info(f"   Produk   : {result['total_products']}")
    logger.info("\n📌 Langkah selanjutnya di terminal Laravel:")
    logger.info("   php artisan db:seed --class=ItemkuCategoriesSeeder")
    logger.info("   php artisan db:seed --class=ItemkuProductsSeeder")
    logger.info("=" * 60)


if __name__ == "__main__":
    asyncio.run(main())
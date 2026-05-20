"""
sync.py - Script utama: Scraping Itemku → Simpan ke Database Laravel
Jalankan: python sync.py
"""

import asyncio
import os
import sys
from dotenv import load_dotenv

from itemku import ItemkuScraper
from repository import upsert_category, upsert_product, get_scrape_summary
from db import get_connection, test_connection
from logger import get_logger

# Load Laravel's .env first, then scraper's .env
laravel_env_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '.env'))
load_dotenv(dotenv_path=laravel_env_path)
load_dotenv()
logger = get_logger("sync")

# ─────────────────────────────────────────────
# MAPPING KATEGORI ITEMKU → KATEGORI DATABASE
# ─────────────────────────────────────────────
# Format: "slug-itemku": {"name": "Nama di DB", "slug": "slug-db"}
# Sesuaikan ini dengan kategori yang ada di tabel `categories` kamu

CATEGORY_MAPPING = {
    "mobile-legends": {
        "name":        "Mobile Legends",
        "slug":        "mobile-legends",
        "description": "Top up dan item Mobile Legends",
        "type":        "topup",
    },
    "free-fire": {
        "name":        "Free Fire",
        "slug":        "free-fire",
        "description": "Diamond dan item Free Fire",
        "type":        "topup",
    },
    "pubg-mobile": {
        "name":        "PUBG Mobile",
        "slug":        "pubg-mobile",
        "description": "UC dan item PUBG Mobile",
        "type":        "topup",
    },
    "genshin-impact": {
        "name":        "Genshin Impact",
        "slug":        "genshin-impact",
        "description": "Genesis Crystals dan item Genshin Impact",
        "type":        "topup",
    },
    "valorant": {
        "name":        "Valorant",
        "slug":        "valorant",
        "description": "VP dan item Valorant",
        "type":        "topup",
    },
    "steam-wallet": {
        "name":        "Steam Wallet",
        "slug":        "steam-wallet",
        "description": "Steam Wallet Code",
        "type":        "voucher",
    },
}


# ─────────────────────────────────────────────
# PROSES SYNC
# ─────────────────────────────────────────────

def save_products_to_db(scraped: dict, seller_user_id: int) -> dict:
    """
    Simpan semua hasil scraping ke database.
    
    scraped: {category_slug: [product_dicts]}
    Return : {"inserted": int, "updated": int, "failed": int}
    """
    conn = get_connection()
    stats = {"inserted": 0, "updated": 0, "failed": 0}

    try:
        for category_slug, products in scraped.items():
            if not products:
                logger.warning(f"⚠️  Tidak ada produk untuk kategori [{category_slug}]")
                continue

            # 1. Pastikan kategori ada di database
            cat_info = CATEGORY_MAPPING.get(
                category_slug,
                {
                    "name":        category_slug.replace("-", " ").title(),
                    "slug":        category_slug,
                    "description": "",
                    "type":        "item",
                }
            )

            category_id = upsert_category(
                conn,
                name        = cat_info["name"],
                slug        = cat_info["slug"],
                description = cat_info.get("description"),
            )

            product_type = cat_info.get("type", "item")

            # 2. Simpan setiap produk
            logger.info(f"\n📦 Menyimpan {len(products)} produk untuk [{cat_info['name']}]...")

            for product in products:
                try:
                    # Tambahkan category_id dan type ke data produk
                    product["category_id"] = category_id
                    product["type"]        = product_type

                    result = upsert_product(conn, product, seller_user_id)
                    stats[result["action"] + "d"] += 1

                except Exception as e:
                    logger.error(f"   ❌ Gagal simpan '{product.get('name', '?')}': {e}")
                    stats["failed"] += 1
                    conn.rollback()

    except Exception as e:
        logger.error(f"❌ Error fatal saat menyimpan: {e}")
        conn.rollback()
    finally:
        conn.close()

    return stats


async def run_sync():
    """Entry point: jalankan scraping dan sync ke database."""
    logger.info("=" * 60)
    logger.info("🚀 MEMULAI SINKRONISASI ITEMKU → DATABASE")
    logger.info("=" * 60)

    # 1. Test koneksi database
    if not test_connection():
        logger.error("❌ Tidak bisa konek ke database. Hentikan proses.")
        sys.exit(1)

    # 2. Ambil konfigurasi
    seller_user_id = int(os.getenv("SELLER_USER_ID", 1))
    raw_categories = os.getenv("TARGET_CATEGORIES", "all")
    if raw_categories.strip().lower() == "all":
        target_categories = list(CATEGORY_MAPPING.keys())
    else:
        target_categories = [c.strip() for c in raw_categories.split(",") if c.strip()]

    logger.info(f"👤 Seller User ID : {seller_user_id}")
    logger.info(f"🎯 Kategori target: {', '.join(target_categories)}")

    # 3. Scraping
    logger.info("\n📡 Memulai proses scraping...")
    scraper = ItemkuScraper()
    scraped = await scraper.run(target_categories)

    total_scraped = sum(len(v) for v in scraped.values())
    logger.info(f"\n✅ Selesai scraping: total {total_scraped} produk")

    if total_scraped == 0:
        logger.warning("⚠️  Tidak ada produk yang berhasil di-scrape.")
        return

    # 4. Simpan ke database
    logger.info("\n💾 Menyimpan ke database MySQL...")
    stats = save_products_to_db(scraped, seller_user_id)

    # 5. Ringkasan
    conn = get_connection()
    summary = get_scrape_summary(conn)
    conn.close()

    logger.info("\n" + "=" * 60)
    logger.info("📊 HASIL SINKRONISASI:")
    logger.info(f"   ✅ Produk baru    : {stats['inserted']}")
    logger.info(f"   🔄 Produk diupdate: {stats['updated']}")
    logger.info(f"   ❌ Gagal          : {stats['failed']}")
    logger.info(f"\n📦 Total produk di DB: {summary['total_products']}")
    logger.info("\n   Per kategori:")
    for cat in summary["by_category"]:
        logger.info(f"   - {cat['name']}: {cat['jumlah']} produk")
    logger.info("=" * 60)


if __name__ == "__main__":
    asyncio.run(run_sync())
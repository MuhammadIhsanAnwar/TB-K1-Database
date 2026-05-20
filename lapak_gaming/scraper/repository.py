"""
repository.py - Semua operasi database (INSERT/UPDATE)
Mapping: data scraping → tabel Laravel
"""

import pymysql
import re
from datetime import datetime
from slugify import slugify
from db import get_connection
from logger import get_logger

logger = get_logger(__name__)


# ─────────────────────────────────────────────
# HELPER
# ─────────────────────────────────────────────

def now() -> str:
    return datetime.now().strftime("%Y-%m-%d %H:%M:%S")


def make_unique_slug(cursor, base_slug: str, table: str = "products") -> str:
    """Buat slug unik, tambahkan suffix angka jika sudah ada."""
    slug = base_slug
    counter = 1
    while True:
        cursor.execute(f"SELECT id FROM `{table}` WHERE slug = %s", (slug,))
        if not cursor.fetchone():
            return slug
        slug = f"{base_slug}-{counter}"
        counter += 1


# ─────────────────────────────────────────────
# CATEGORY
# ─────────────────────────────────────────────

def upsert_category(conn, name: str, slug: str, parent_id=None, description: str = None) -> int:
    """
    Insert category jika belum ada, return id-nya.
    Mapping ke tabel: categories
    """
    with conn.cursor() as cursor:
        # Cek apakah kategori sudah ada berdasarkan slug
        cursor.execute("SELECT id FROM categories WHERE slug = %s", (slug,))
        row = cursor.fetchone()
        if row:
            return row["id"]

        # Pastikan slug unik
        unique_slug = make_unique_slug(cursor, slug, "categories")

        cursor.execute("""
            INSERT INTO categories (parent_id, name, slug, description, sort_order, is_active, created_at, updated_at)
            VALUES (%s, %s, %s, %s, 0, 1, %s, %s)
        """, (parent_id, name, unique_slug, description, now(), now()))

        category_id = cursor.lastrowid
        conn.commit()
        logger.info(f"📁 Kategori baru: [{category_id}] {name}")
        return category_id


# ─────────────────────────────────────────────
# PRODUCT
# ─────────────────────────────────────────────

def upsert_product(conn, data: dict, seller_user_id: int) -> dict:
    """
    Insert atau update produk berdasarkan slug.
    
    data keys yang diharapkan:
        name, price, sale_price, description, stock,
        type, category_id, source_url, image_url,
        sold_count, rating_average, review_count
    
    Mapping ke tabel:
        - products          → data utama produk
        - product_statistics → statistik (sold, rating, dll)
    
    Return: {"id": int, "action": "inserted"|"updated"}
    """
    name = data.get("name", "").strip()
    if not name:
        raise ValueError("Nama produk tidak boleh kosong")

    base_slug = slugify(name, allow_unicode=False, max_length=200)

    with conn.cursor() as cursor:
        # Cek apakah produk sudah ada (berdasarkan nama + seller)
        cursor.execute("""
            SELECT id, price, sale_price, stock
            FROM products
            WHERE seller_id = %s AND name = %s
            LIMIT 1
        """, (seller_user_id, name))
        existing = cursor.fetchone()

        price       = float(data.get("price", 0))
        sale_price  = float(data.get("sale_price")) if data.get("sale_price") else None
        stock       = int(data.get("stock", 0))
        description = data.get("description", "")
        product_type = data.get("type", "item")
        category_id  = data.get("category_id")

        if existing:
            product_id = existing["id"]
            old_price  = float(existing["price"])

            # Update harga, stok, dan deskripsi saja (tidak overwrite semua)
            cursor.execute("""
                UPDATE products
                SET price = %s, sale_price = %s, stock = %s,
                    description = %s, updated_at = %s
                WHERE id = %s
            """, (price, sale_price, stock, description, now(), product_id))

            conn.commit()

            if old_price != price:
                logger.info(f"💰 Harga update [{product_id}] {name}: Rp{old_price:,.0f} → Rp{price:,.0f}")
            else:
                logger.debug(f"🔄 Update produk [{product_id}] {name}")

            action = "updated"

        else:
            # Slug unik
            unique_slug = make_unique_slug(cursor, base_slug)

            cursor.execute("""
                INSERT INTO products
                    (seller_id, category_id, name, slug, description,
                     price, sale_price, stock, type,
                     is_auto_delivery, is_featured, is_trending,
                     status, views_count, created_at, updated_at)
                VALUES
                    (%s, %s, %s, %s, %s,
                     %s, %s, %s, %s,
                     1, 0, 0,
                     'published', 0, %s, %s)
            """, (
                seller_user_id, category_id, name, unique_slug, description,
                price, sale_price, stock, product_type,
                now(), now()
            ))

            product_id = cursor.lastrowid
            conn.commit()
            logger.info(f"✅ Produk baru [{product_id}] {name} - Rp{price:,.0f}")
            action = "inserted"

        # Upsert product_statistics
        _upsert_product_statistics(cursor, product_id, data)
        conn.commit()

    return {"id": product_id, "action": action}


def _upsert_product_statistics(cursor, product_id: int, data: dict):
    """
    Insert/update product_statistics dari data scraping.
    Mapping ke tabel: product_statistics
    
    Fields yang diisi:
        sold_count, rating_average, review_count
    """
    sold_count     = int(data.get("sold_count", 0))
    rating_average = float(data.get("rating_average", 0.0))
    review_count   = int(data.get("review_count", 0))

    cursor.execute("SELECT id FROM product_statistics WHERE product_id = %s", (product_id,))
    row = cursor.fetchone()

    if row:
        cursor.execute("""
            UPDATE product_statistics
            SET sold_count = %s, rating_average = %s, review_count = %s, updated_at = %s
            WHERE product_id = %s
        """, (sold_count, rating_average, review_count, now(), product_id))
    else:
        cursor.execute("""
            INSERT INTO product_statistics
                (product_id, sold_count, rating_average, review_count, views_count, downloads_count, created_at, updated_at)
            VALUES
                (%s, %s, %s, %s, 0, 0, %s, %s)
        """, (product_id, sold_count, rating_average, review_count, now(), now()))


# ─────────────────────────────────────────────
# STATISTIK SCRAPING
# ─────────────────────────────────────────────

def get_scrape_summary(conn) -> dict:
    """Ambil ringkasan data produk di database."""
    with conn.cursor() as cursor:
        cursor.execute("SELECT COUNT(*) as total FROM products WHERE status = 'published'")
        total = cursor.fetchone()["total"]

        cursor.execute("""
            SELECT c.name, COUNT(p.id) as jumlah
            FROM products p
            JOIN categories c ON c.id = p.category_id
            GROUP BY c.name
            ORDER BY jumlah DESC
        """)
        by_category = cursor.fetchall()

    return {"total_products": total, "by_category": by_category}
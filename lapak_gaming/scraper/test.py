"""
test.py - Testing komponen scraper (tanpa koneksi DB)
Jalankan: python test.py
"""

import asyncio
import os
from dotenv import load_dotenv

load_dotenv()


def test_imports():
    print("\n[TEST 1] Import library...")
    try:
        from playwright.async_api import async_playwright
        from bs4 import BeautifulSoup
        from slugify import slugify
        import colorlog
        print("   ✅ PASS - Semua library tersedia")
        return True
    except ImportError as e:
        print(f"   ❌ FAIL: {e}")
        print("   💡 Jalankan: pip install -r requirements.txt && playwright install chromium")
        return False


def test_env():
    print("\n[TEST 2] Konfigurasi .env...")
    required = ["TARGET_CATEGORIES", "SELLER_USER_ID", "OUTPUT_DIR"]
    missing  = [k for k in required if not os.getenv(k)]
    if missing:
        print(f"   ⚠️  Variabel belum diset: {', '.join(missing)}")
        print("   💡 Copy .env.example ke .env lalu isi nilainya")
    else:
        print(f"   ✅ PASS")

    print(f"   TARGET_CATEGORIES : {os.getenv('TARGET_CATEGORIES', '(belum diset)')}")
    print(f"   SELLER_USER_ID    : {os.getenv('SELLER_USER_ID', '(belum diset)')}")
    print(f"   OUTPUT_DIR        : {os.getenv('OUTPUT_DIR', '(belum diset)')}")
    return True


def test_slug_and_mapping():
    print("\n[TEST 3] Slug generation & type mapping...")
    try:
        from seeder_generator import make_slug, map_product_type, CATEGORY_MAPPING

        slug_set = set()
        s1 = make_slug("Mobile Legends 500 Diamond", slug_set)
        s2 = make_slug("Mobile Legends 500 Diamond", slug_set)  # harus dapat suffix
        assert s1 != s2, "Slug duplikat tidak dicegah"
        assert "mobile-legends" in s1

        assert map_product_type("Top Up Diamond")  == "topup"
        assert map_product_type("Akun Sultan")     == "akun"
        assert map_product_type("Gift Card Steam") == "voucher"

        print(f"   ✅ PASS  (contoh slug: '{s1}' / '{s2}')")
        return True
    except Exception as e:
        print(f"   ❌ FAIL: {e}")
        return False


def test_seeder_generation():
    print("\n[TEST 4] Generate seeder dari data dummy...")
    try:
        import tempfile
        from seeder_generator import generate_all

        dummy_scraped = {
            "mobile-legends": [
                {
                    "name": "Mobile Legends 500 Diamond",
                    "price": 85000, "sale_price": 80000,
                    "description": "Top up 500 diamond Mobile Legends",
                    "stock": 999, "sold_count": 1234,
                    "rating_average": 4.8, "review_count": 56,
                    "source_url": "https://itemku.com/test", "image_url": "", "external_id": "001",
                },
                {
                    "name": "Mobile Legends 1000 Diamond",
                    "price": 160000, "sale_price": None,
                    "description": "Top up 1000 diamond Mobile Legends",
                    "stock": 500, "sold_count": 800,
                    "rating_average": 4.9, "review_count": 30,
                    "source_url": "https://itemku.com/test2", "image_url": "", "external_id": "002",
                },
            ],
            "free-fire": [
                {
                    "name": "Free Fire 100 Diamond",
                    "price": 15000, "sale_price": None,
                    "description": "Top up 100 diamond Free Fire",
                    "stock": 999, "sold_count": 500,
                    "rating_average": 4.5, "review_count": 20,
                    "source_url": "", "image_url": "", "external_id": "003",
                },
            ],
        }

        with tempfile.TemporaryDirectory() as tmpdir:
            result = generate_all(dummy_scraped, seller_user_id=1, output_dir=tmpdir)

            # Verifikasi file dibuat
            import os
            cat_file  = result["categories_seeder"]
            prod_file = result["products_seeder"]

            assert os.path.exists(cat_file),  "Categories seeder tidak dibuat"
            assert os.path.exists(prod_file), "Products seeder tidak dibuat"

            # Verifikasi konten PHP dasar
            cat_content = open(cat_file, encoding="utf-8").read()
            prod_content = open(prod_file, encoding="utf-8").read()

            assert "ItemkuCategoriesSeeder" in cat_content
            assert "ItemkuProductsSeeder"  in prod_content
            assert "mobile-legends"        in cat_content
            assert "500 Diamond"           in prod_content
            assert "85000.00"              in prod_content

        print(f"   ✅ PASS - {result['total_categories']} kategori, {result['total_products']} produk")
        return True

    except Exception as e:
        print(f"   ❌ FAIL: {e}")
        import traceback; traceback.print_exc()
        return False


async def test_scraper_browser():
    print("\n[TEST 5] Scraping browser — kategori: mobile-legends...")
    print("   ⏳ Membuka browser, tunggu 15-30 detik...")
    try:
        from itemku import ItemkuScraper

        scraper = ItemkuScraper()
        results = await scraper.run(["mobile-legends"])
        products = results.get("mobile-legends", [])

        print(f"   Produk ditemukan: {len(products)}")
        if products:
            s = products[0]
            print(f"   Contoh: '{s.get('name')}' — Rp{s.get('price', 0):,.0f}")
            print(f"   ✅ PASS")
            return True
        else:
            print("   ⚠️  0 produk ditemukan. Cek selector di itemku.py.")
            return False
    except Exception as e:
        print(f"   ❌ FAIL: {e}")
        return False


def run_all():
    print("=" * 60)
    print("🧪 ITEMKU SCRAPER → SEEDER TEST SUITE")
    print("=" * 60)

    results = {}
    results["imports"]           = test_imports()
    results["env"]               = test_env()
    results["slug_mapping"]      = test_slug_and_mapping()
    results["seeder_generation"] = test_seeder_generation()

    run_browser = input("\nTest scraping browser? (y/N): ").strip().lower() == "y"
    if run_browser:
        results["scraper_browser"] = asyncio.run(test_scraper_browser())
    else:
        print("\n[TEST 5] ⏭️  Dilewati")
        results["scraper_browser"] = None

    print("\n" + "=" * 60)
    print("RINGKASAN:")
    all_pass = True
    for name, passed in results.items():
        if passed is None:
            print(f"   ⏭️  {name.replace('_', ' ').title()}: SKIP")
        elif passed:
            print(f"   ✅ {name.replace('_', ' ').title()}: PASS")
        else:
            print(f"   ❌ {name.replace('_', ' ').title()}: FAIL")
            all_pass = False
    print("=" * 60)

    if all_pass:
        print("🎉 Siap! Jalankan: python run.py")
    else:
        print("⚠️  Ada test gagal. Perbaiki sebelum lanjut.")


if __name__ == "__main__":
    run_all()
"""
itemku.py - Scraper utama Itemku
Versi stabil:
✅ Playwright browser automation
✅ Auto accept cookie popup
✅ Scroll lazy loading
✅ Intercept API internal
✅ Fallback HTML parsing
✅ Debug screenshot + HTML dump
✅ Support struktur URL Itemku terbaru
"""

import asyncio
import json
import os
import random
import re
import time
from typing import Optional

from bs4 import BeautifulSoup
from dotenv import load_dotenv
from playwright.async_api import async_playwright, Page, BrowserContext

from logger import get_logger

load_dotenv()
logger = get_logger(__name__)

# =========================================================
# CONFIG
# =========================================================

BASE_URL = os.getenv("ITEMKU_BASE_URL", "https://www.itemku.com")

DELAY_MIN = float(os.getenv("REQUEST_DELAY_MIN", 2))
DELAY_MAX = float(os.getenv("REQUEST_DELAY_MAX", 5))

MAX_PRODUCTS = int(os.getenv("MAX_PRODUCTS_PER_CATEGORY", 50))

HEADLESS = os.getenv("HEADLESS", "false").lower() == "true"

DEBUG_DIR = os.getenv("DEBUG_DIR", "debug")

os.makedirs(DEBUG_DIR, exist_ok=True)


# =========================================================
# UTILITAS
# =========================================================

def random_delay():
    t = random.uniform(DELAY_MIN, DELAY_MAX)
    logger.debug(f"⏳ Delay {t:.1f}s...")
    time.sleep(t)


def parse_price(raw: str) -> Optional[float]:
    if not raw:
        return None

    cleaned = re.sub(r"[^\d]", "", str(raw))
    return float(cleaned) if cleaned else None


def parse_number(raw: str) -> int:
    if not raw:
        return 0

    cleaned = re.sub(r"[^\d]", "", str(raw))
    return int(cleaned) if cleaned else 0


def parse_rating(raw: str) -> float:
    if not raw:
        return 0.0

    try:
        match = re.search(r"[\d.]+", str(raw))
        return float(match.group()) if match else 0.0
    except:
        return 0.0


# =========================================================
# SCRAPER
# =========================================================

class ItemkuScraper:

    def __init__(self):
        self.browser = None
        self.context = None
        self.playwright = None

    # =====================================================
    # START BROWSER
    # =====================================================

    async def _start_browser(self):

        self.playwright = await async_playwright().start()

        self.browser = await self.playwright.chromium.launch(
            headless=HEADLESS,
            args=[
                "--disable-blink-features=AutomationControlled",
                "--disable-dev-shm-usage",
                "--no-sandbox",
            ]
        )

        self.context = await self.browser.new_context(
            user_agent=(
                "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
                "AppleWebKit/537.36 (KHTML, like Gecko) "
                "Chrome/124.0.0.0 Safari/537.36"
            ),
            viewport={"width": 1366, "height": 768},
            locale="id-ID",
        )

        logger.info("🌐 Browser berhasil dijalankan")

    # =====================================================
    # STOP BROWSER
    # =====================================================

    async def _stop_browser(self):

        if self.context:
            await self.context.close()

        if self.browser:
            await self.browser.close()

        if self.playwright:
            await self.playwright.stop()

        logger.info("🔒 Browser ditutup")

    # =====================================================
    # INTERCEPT API
    # =====================================================

    async def _setup_api_interceptor(self, page: Page):

        captured = []

        async def handle_response(response):

            try:
                url = response.url.lower()

                if any(k in url for k in [
                    "/api/",
                    "graphql",
                    "product",
                    "search",
                    "catalog",
                    "item",
                ]):

                    content_type = response.headers.get("content-type", "")

                    if "application/json" in content_type:

                        data = await response.json()

                        captured.append({
                            "url": url,
                            "data": data
                        })

                        logger.debug(f"📡 API: {url}")

            except:
                pass

        page.on("response", handle_response)

        return captured

    # =====================================================
    # SCRAPE CATEGORY
    # =====================================================

    async def scrape_category(self, category_slug: str):
        url_slug = category_slug
        if category_slug == "free-fire":
            url_slug = "garena-free-fire"
        url = f"{BASE_URL}/g/{url_slug}"

        logger.info(f"🔍 Scrape kategori:")
        logger.info(f"   URL: {url}")

        page = await self.context.new_page()

        products = []
        try:
            import urllib.request
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})
            with urllib.request.urlopen(req, timeout=30) as response:
                html = response.read().decode('utf-8')
                
            from bs4 import BeautifulSoup
            soup = BeautifulSoup(html, "html.parser")
            
            logger.info("💾 Simpan debug...")
            with open(f"{DEBUG_DIR}/debug.html", "w", encoding="utf-8") as f:
                f.write(html)
                
            next_data = soup.find("script", {"id": "__NEXT_DATA__"})
            if next_data:
                logger.info("📦 __NEXT_DATA__ ditemukan")
                try:
                    data = json.loads(next_data.string)
                    with open(f"{DEBUG_DIR}/next_data.json", "w", encoding="utf-8") as f:
                        json.dump(data, f, indent=2)
                    
                    page_products = data.get('props', {}).get('pageProps', {}).get('products', [])
                    if page_products:
                        logger.info(f"✅ Produk dari __NEXT_DATA__: {len(page_products)}")
                        for p in page_products:
                            products.append({
                                "name": p.get("name", ""),
                                "price": p.get("price", 0),
                                "sale_price": p.get("price", 0),
                                "description": p.get("item_info_name", ""),
                                "stock": p.get("total_products", 999),
                                "sold_count": 0,
                                "rating_average": 0.0,
                                "review_count": 0,
                                "source_url": f"{BASE_URL}/p/{p.get('seo_string', '')}" if p.get('seo_string') else "",
                                "image_url": p.get("icon_image_url", ""),
                                "external_id": str(p.get("id", "")),
                            })
                        return products[:MAX_PRODUCTS]
                except Exception as e:
                    logger.error(f"❌ Gagal parse NEXT_DATA: {e}")
                    
        except Exception as e:
            logger.error(f"❌ Error scrape: {e}")

        return products[:MAX_PRODUCTS]

    # =====================================================
    # PARSE API
    # =====================================================

    def _parse_api(self, captured):

        products = []

        for item in captured:

            data = item.get("data", {})

            possible_lists = [
                data.get("data"),
                data.get("products"),
                data.get("items"),
                data.get("catalog"),
            ]

            for raw_list in possible_lists:

                if isinstance(raw_list, list):

                    for raw in raw_list:

                        product = self._normalize_product(raw)

                        if product:
                            products.append(product)

        return products

    # =====================================================
    # NORMALIZE PRODUCT
    # =====================================================

    def _normalize_product(self, raw):

        try:

            name = (
                raw.get("name")
                or raw.get("title")
                or raw.get("product_name")
                or ""
            ).strip()

            if not name:
                return None

            return {
                "name": name,

                "price": parse_price(
                    raw.get("price")
                    or raw.get("selling_price")
                    or raw.get("min_price")
                    or 0
                ) or 0,

                "sale_price": parse_price(
                    raw.get("sale_price")
                    or raw.get("discount_price")
                ),

                "description": raw.get("description", ""),

                "stock": int(
                    raw.get("stock", 999)
                    or 999
                ),

                "sold_count": parse_number(
                    raw.get("sold_count", 0)
                ),

                "rating_average": parse_rating(
                    raw.get("rating", 0)
                ),

                "review_count": parse_number(
                    raw.get("review_count", 0)
                ),

                "source_url": raw.get("url", ""),

                "image_url": (
                    raw.get("image")
                    or raw.get("thumbnail")
                    or ""
                ),

                "external_id": str(
                    raw.get("id", "")
                ),
            }

        except:
            return None

    # =====================================================
    # PARSE HTML
    # =====================================================

    def _parse_html(self, html):

        soup = BeautifulSoup(html, "html.parser")

        products = []

        # =============================================
        # NEXT DATA
        # =============================================

        next_data = soup.find("script", {"id": "__NEXT_DATA__"})

        if next_data:

            logger.info("📦 __NEXT_DATA__ ditemukan")

            try:

                data = json.loads(next_data.string)

                with open(
                    f"{DEBUG_DIR}/next_data.json",
                    "w",
                    encoding="utf-8"
                ) as f:
                    json.dump(data, f, indent=2)

                logger.info("💾 next_data.json disimpan")
                
                # Extract products from __NEXT_DATA__
                page_products = data.get('props', {}).get('pageProps', {}).get('products', [])
                if page_products:
                    logger.info(f"✅ Produk dari __NEXT_DATA__: {len(page_products)}")
                    for p in page_products:
                        products.append({
                            "name": p.get("name", ""),
                            "price": p.get("price", 0),
                            "sale_price": None,
                            "description": p.get("item_info_name", ""),
                            "stock": p.get("total_products", 999),
                            "sold_count": 0,
                            "rating_average": 0.0,
                            "review_count": 0,
                            "source_url": f"{BASE_URL}/p/{p.get('seo_string', '')}" if p.get('seo_string') else "",
                            "image_url": p.get("icon_image_url", ""),
                            "external_id": str(p.get("id", "")),
                        })
                    return products

            except Exception as e:
                logger.error(f"❌ Gagal parse NEXT_DATA: {e}")

        # =============================================
        # SELECTOR PRODUK
        # =============================================

        selectors = [
            "div[data-testid='product-card']",
            ".product-card",
            ".item-card",
            "[class*='product']",
            "[class*='Product']",
            "article",
        ]

        cards = []

        for sel in selectors:

            cards = soup.select(sel)

            if cards:
                logger.info(f"✅ Selector cocok: {sel}")
                logger.info(f"📦 Jumlah card: {len(cards)}")
                break

        if not cards:

            logger.warning("⚠️ Tidak ada produk ditemukan")
            return []

        # =============================================
        # PARSE CARD
        # =============================================

        for card in cards:

            try:

                name_el = (
                    card.select_one("h2")
                    or card.select_one("h3")
                    or card.select_one(".title")
                    or card.select_one(".name")
                )

                if not name_el:
                    continue

                name = name_el.get_text(strip=True)

                price_el = (
                    card.select_one(".price")
                    or card.select_one("[class*='price']")
                )

                price = parse_price(
                    price_el.get_text(strip=True)
                    if price_el else "0"
                )

                img_el = card.select_one("img")

                image_url = ""

                if img_el:
                    image_url = (
                        img_el.get("src")
                        or img_el.get("data-src")
                        or ""
                    )

                link_el = card.select_one("a[href]")

                source_url = ""

                if link_el:
                    source_url = link_el.get("href", "")

                    if source_url.startswith("/"):
                        source_url = BASE_URL + source_url

                products.append({
                    "name": name,
                    "price": price or 0,
                    "sale_price": None,
                    "description": "",
                    "stock": 999,
                    "sold_count": 0,
                    "rating_average": 0,
                    "review_count": 0,
                    "source_url": source_url,
                    "image_url": image_url,
                    "external_id": "",
                })

            except Exception as e:
                logger.debug(f"Gagal parse card: {e}")

        return products

    # =====================================================
    # RUN
    # =====================================================

    async def run(self, categories):

        await self._start_browser()

        results = {}

        try:

            for category in categories:

                logger.info("\n" + "=" * 60)
                logger.info(f"📂 KATEGORI: {category}")
                logger.info("=" * 60)

                products = await self.scrape_category(category)

                results[category] = products

                logger.info(
                    f"📦 Total produk: {len(products)}"
                )

                random_delay()

        finally:

            await self._stop_browser()

        return results


# =========================================================
# MAIN
# =========================================================

if __name__ == "__main__":

    categories = os.getenv(
        "TARGET_CATEGORIES",
        "tipe-produk/top-up"
    ).split(",")

    categories = [
        c.strip()
        for c in categories
        if c.strip()
    ]

    scraper = ItemkuScraper()

    results = asyncio.run(
        scraper.run(categories)
    )

    print("\n" + "=" * 60)
    print("HASIL SCRAPING")

    for category, products in results.items():
        print(f"{category}: {len(products)} produk")

    print("=" * 60)
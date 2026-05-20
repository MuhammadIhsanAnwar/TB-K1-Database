"""
db.py - Koneksi ke Database MySQL Laravel
"""

import pymysql
import os
from dotenv import load_dotenv
from logger import get_logger

load_dotenv()
logger = get_logger(__name__)


def get_connection():
    """Buat koneksi ke database MySQL."""
    try:
        conn = pymysql.connect(
            host=os.getenv("DB_HOST", "127.0.0.1"),
            port=int(os.getenv("DB_PORT", 3306)),
            user=os.getenv("DB_USER", "root"),
            password=os.getenv("DB_PASSWORD", ""),
            database=os.getenv("DB_NAME"),
            charset="utf8mb4",
            cursorclass=pymysql.cursors.DictCursor,
            autocommit=False,
        )
        return conn
    except pymysql.Error as e:
        logger.error(f"❌ Gagal konek ke database: {e}")
        raise


def test_connection():
    """Test apakah koneksi database berhasil."""
    try:
        conn = get_connection()
        with conn.cursor() as cursor:
            cursor.execute("SELECT 1")
        conn.close()
        logger.info("✅ Koneksi database berhasil!")
        return True
    except Exception as e:
        logger.error(f"❌ Koneksi database gagal: {e}")
        return False
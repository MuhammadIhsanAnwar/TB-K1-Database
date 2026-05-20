"""
scheduler.py - Scheduler otomatis untuk sinkronisasi berkala
Jalankan: python scheduler.py
"""

import asyncio
import schedule
import time
from sync import run_sync
from logger import get_logger

logger = get_logger("scheduler")


def job():
    """Jalankan sync secara sinkron (dipanggil oleh scheduler)."""
    logger.info("⏰ Scheduler memulai sinkronisasi...")
    try:
        asyncio.run(run_sync())
    except Exception as e:
        logger.error(f"❌ Error pada scheduled job: {e}")


def start_scheduler():
    """
    Konfigurasi jadwal scraping.
    Sesuaikan interval sesuai kebutuhan.
    """
    # Jalankan setiap 6 jam
    schedule.every(6).hours.do(job)

    # Atau: setiap hari jam 7 pagi dan 7 malam
    # schedule.every().day.at("07:00").do(job)
    # schedule.every().day.at("19:00").do(job)

    # Atau: setiap 30 menit (tidak disarankan, bisa kena block)
    # schedule.every(30).minutes.do(job)

    logger.info("📅 Scheduler berjalan. Jadwal:")
    for job_item in schedule.get_jobs():
        logger.info(f"   - {job_item}")

    # Jalankan sekali saat start
    logger.info("▶️  Menjalankan sinkronisasi pertama...")
    job()

    # Loop scheduler
    while True:
        schedule.run_pending()
        time.sleep(30)


if __name__ == "__main__":
    start_scheduler()
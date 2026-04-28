Panduan deploy Laravel ke cPanel tanpa SSH
=========================================

Langkah singkat (tanpa SSH):

1. Siapkan file `.env` lokal berdasarkan `.env.example`:
   - Ubah `APP_URL` ke `https://your-domain.com`.
   - Set `APP_ENV=production` dan `APP_DEBUG=false`.
   - Isi `DB_CONNECTION`, `DB_HOST` (biasanya `localhost` atau host DB dari cPanel), `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
   - Simpan sebagai `.env` di root proyek sebelum upload.

2. Pastikan `vendor/` sudah terpasang:
   - Jika tidak ada Composer di server, jalankan `composer install --no-dev --optimize-autoloader` di mesin lokal.
   - Upload folder `vendor/` hasilnya ke server (gunakan SFTP/FTP).

3. Upload file proyek ke cPanel:
   - Letakkan seluruh isi project di dalam folder di `home/username/` (mis. `lapak_gaming`).
   - Jika host mengharuskan dokumen root di `public_html`, pindahkan isi `public/` (bukan foldernya) ke `public_html/` dan sesuaikan path di `index.php`.

4. Menyesuaikan `public/index.php` bila memindahkan `public` ke `public_html`:
   - Di `public_html/index.php` set path autoload dan bootstrap. Contoh ketika project ada di `lapak_gaming`:

```php
$autoloadFile = __DIR__.'/../lapak_gaming/vendor/autoload.php';
$bootstrapFile = __DIR__.'/../lapak_gaming/bootstrap/app.php';
```

5. Set permission (via cPanel File Manager):
   - `storage` dan `bootstrap/cache` harus bisa ditulis oleh webserver. Atur permission ke `755` atau `775` tergantung host.

6. Generate `APP_KEY` tanpa SSH:
   - Generate lokal: jalankan `php artisan key:generate --show` pada mesin lokal, salin hasilnya.
   - Paste ke `.env` pada `APP_KEY=`.

7. Buat symlink storage (jika perlu) tanpa SSH:
   - Jika tidak bisa buat symlink, ubah `FILESYSTEM_DISK=public` dan upload folder `storage/app/public` ke `public_html/storage` lalu sesuaikan referensi.

8. Cache config & routes (dengan akses SSH lebih baik). Jika tidak ada, pastikan `.env` benar lalu refresh cache browser.

9. Cek log jika error 500:
   - Buka `storage/logs/laravel.log` via File Manager untuk pesan error.
   - Aktifkan sementara `APP_DEBUG=true` di `.env` untuk melihat error di browser (ingat matikan setelah selesai).

Catatan penting:
- Jangan upload `.env` ke repository publik.
- Jika menggunakan database MySQL cPanel, buat database dan user di "MySQL Databases" lalu pasangkan kredensial di `.env`.

Jika mau, saya bantu membuat contoh `.env` (Anda hanya mengganti kredensial sensitif), atau membuat patch `public/index.php` contoh jika Anda memindahkan `public` ke `public_html`.

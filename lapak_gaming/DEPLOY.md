Panduan deploy Laravel ke cPanel (Production dengan Tailwind CSS + Vite)
=====================================================================

⚠️ PENTING: Build Assets Sebelum Upload!
=========================================
Langkah pertama yang WAJIB dilakukan di mesin lokal sebelum upload ke server:

```bash
cd D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming
npm install          # Jika belum pernah install dependency npm
npm run build        # BUILD ASSETS UNTUK PRODUCTION - SANGAT PENTING!
```

Ini akan generate folder `public/build/` berisi CSS dan JS production yang sudah di-minify.
Folder ini HARUS di-upload ke server!

---

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
   - Jika deploy memakai fitur **Git Version Control / Update from Remote**, pastikan hasil `npm run build` ikut ter-deploy. Git pull di cPanel **tidak** menjalankan build Tailwind/Vite otomatis.
   - Artinya, folder `public/build/` harus sudah ada di repo yang di-pull, atau harus di-upload manual setelah build lokal selesai.

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

---

✅ CHECKLIST SEBELUM PRODUCTION
================================

Pastikan sudah selesai semua di mesin lokal SEBELUM upload:

- [ ] Jalankan `npm install` 
- [ ] Jalankan `npm run build` (buat folder `public/build/`)
- [ ] Update `.env`:
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_URL=https://lapakgaming.neoverse.my.id`
  - [ ] Database credentials sudah benar
  - [ ] `APP_KEY` sudah ada
- [ ] Jalankan `composer install --no-dev --optimize-autoloader`
- [ ] Folder `storage` & `bootstrap/cache` ada permissions write

File & Folder yang WAJIB di-upload ke server:
- [ ] `app/` 
- [ ] `bootstrap/`
- [ ] `config/`
- [ ] `database/`
- [ ] `public/` (termasuk `public/build/` hasil npm run build)
- [ ] `resources/`
- [ ] `routes/`
- [ ] `storage/` 
- [ ] `vendor/` (dari `composer install --no-dev`)
- [ ] `.env` (jangan lupa!)
- [ ] `artisan`, `composer.json`, `package.json`

Setelah upload ke server:
- [ ] Set permissions storage & bootstrap/cache ke 755 atau 775
- [ ] Buka website, pastikan Tailwind CSS loading (buka DevTools - Network tab)
- [ ] Cek `storage/logs/laravel.log` jika ada error

---

🚀 DEPLOYMENT FLOW RINGKAS
============================

**Di mesin lokal (Windows):**
```bash
npm install
npm run build
composer install --no-dev --optimize-autoloader
```

**Di cPanel:**
- Upload semua file (gunakan SFTP/FTP)
- Set permissions storage & bootstrap/cache
- Buka website dan test

**Jika memakai cPanel Git update:**
- Jalankan `npm run build` di laptop dulu
- Pastikan `public/build/` ikut masuk ke server saat update repo
- Jangan berharap `npm run dev` atau build otomatis terjadi di hosting, karena cPanel Git hanya mengambil file, bukan membangun asset

**Tidak perlu:**
- ❌ `npm run dev` di production (tidak perlu install Node.js di server)
- ❌ Jalankan `php artisan serve`
- ❌ Generate APP_KEY lagi (sudah ada di .env lokal)

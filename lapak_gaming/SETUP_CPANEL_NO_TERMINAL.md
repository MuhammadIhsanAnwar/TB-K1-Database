# Setup Laravel di cPanel Tanpa Terminal

## Langkah 1: Install Dependencies di Local (Windows)

1. **Install Composer di Windows** (jika belum):
   - Download: https://getcomposer.org/Composer-Setup.exe
   - Install dengan default settings

2. **Install vendor dependencies di local**:
   ```cmd
   cd d:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming
   composer install --no-dev --optimize-autoloader
   ```

3. **Tunggu sampai selesai** (akan download ~50-100MB dependencies)

## Langkah 2: Upload Files ke cPanel

1. **Compress folder Laravel** (kecuali node_modules):
   - Klik kanan folder `lapak_gaming`
   - Send to → Compressed (zipped) folder
   - Nama: `lapak_gaming.zip`

2. **Upload via cPanel File Manager**:
   - Login cPanel
   - File Manager → `public_html/lapakgaming.neoverse.my.id/`
   - Upload `lapak_gaming.zip`
   - Extract zip file
   - Hapus zip file setelah extract

3. **Verify folder structure**:
   ```
   public_html/
   └── lapakgaming.neoverse.my.id/
       └── lapak_gaming/
           ├── .htaccess        ← PENTING!
           ├── .env             ← PENTING!
           ├── public/
           │   ├── index.php
           │   ├── setup.php    ← File setup baru
           │   └── migrate.php  ← File migration baru
           ├── vendor/          ← Folder composer (wajib ada!)
           ├── app/
           ├── config/
           └── ...
   ```

## Langkah 3: Set Document Root di cPanel

1. **cPanel → Domains → Manage → lapakgaming.neoverse.my.id**
2. **Document Root**: 
   ```
   public_html/lapakgaming.neoverse.my.id/lapak_gaming
   ```
   *(Tanpa /public, karena .htaccess akan redirect)*

3. **Save & tunggu 2-5 menit**

## Langkah 4: Jalankan Setup via Browser

### 4.1. Setup Laravel:
```
https://lapakgaming.neoverse.my.id/setup.php
```

Script ini akan otomatis:
- ✅ Check PHP version & extensions
- ✅ Set permissions untuk storage/ dan bootstrap/cache/
- ✅ Clear old cache files
- ✅ Generate APP_KEY
- ✅ Test database connection
- ✅ Create storage symbolic link

### 4.2. Jalankan Migration:
```
https://lapakgaming.neoverse.my.id/migrate.php
```

Klik tombol:
1. **"Run Migration"** → Buat semua tables
2. **"Run Seeders"** → Isi data test (admin, products, dll)

## Langkah 5: Test Website

Buka: https://lapakgaming.neoverse.my.id

**Login dengan akun test:**
- Admin: `admin@lapakgaming.com` / `password`
- Buyer: `buyer1@example.com` / `password`
- Seller: `seller1@example.com` / `password`

## Langkah 6: Security (PENTING!)

**Hapus file setup** setelah selesai:
1. Via cPanel File Manager:
   - `public/setup.php` → Delete
   - `public/migrate.php` → Delete

2. Atau via FTP

## Troubleshooting

### Error 500
1. Check `.env` file exist dan DB credentials benar
2. Check `vendor/` folder ada dan lengkap
3. Check permissions via setup.php

### "vendor/autoload.php not found"
- Upload ulang folder `vendor/` dari local
- Pastikan hasil `composer install` sudah selesai di local

### Database connection error
- Verify credentials di `.env`:
  ```
  DB_DATABASE=neoz6813_TB-K1-Database
  DB_USERNAME=neoz6813
  DB_PASSWORD=@Webihsananwar33
  DB_HOST=localhost
  ```

### White screen / blank page
- Enable debug mode sementara di `.env`:
  ```
  APP_DEBUG=true
  ```
- Refresh page untuk lihat error detail
- Matikan lagi setelah fix: `APP_DEBUG=false`

## File Permissions (Manual via cPanel)

Jika setup.php gagal set permissions:

1. File Manager → `lapak_gaming/storage` → klik kanan → Change Permissions
2. Set: **0775** (rwxrwxr-x)
3. ✅ Check "Recurse into subdirectories"
4. Apply

Ulangi untuk `bootstrap/cache/`

## Backup Database

Setelah setup berhasil:
- cPanel → phpMyAdmin
- Select database `neoz6813_TB-K1-Database`
- Export → Quick → Go
- Simpan backup SQL file

---

**Catatan**: Method ini aman dan recommended untuk shared hosting tanpa SSH/Terminal.

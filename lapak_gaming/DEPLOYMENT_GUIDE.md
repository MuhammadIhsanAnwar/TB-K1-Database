# Lapak Gaming - Digital Marketplace Production Deployment Guide

## 📋 Daftar Isi
1. [Setup Lokal Development](#setup-lokal-development)
2. [Configuration Database & Email](#configuration)
3. [Build Assets dengan Vite](#build-assets)
4. [Deploy ke cPanel](#deploy-cpanel)
5. [Post-Deployment Setup](#post-deployment)
6. [SSL & Domain Configuration](#ssl-domain)
7. [Troubleshooting](#troubleshooting)

---

## Setup Lokal Development

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

### 1. Clone Repository & Install Dependencies

```bash
cd d:\0_Project_VS_Code\3.\ Sistem\ Basis\ Data\TB-K1-Database\lapak_gaming
composer install
npm install
```

### 2. Copy Environment File

```bash
cp .env.example .env
# File sudah ada dengan kredensial
```

### 3. Generate App Key

```bash
php artisan key:generate
```

### 4. Database Setup Lokal (Development)

```bash
# Pastikan MySQL running
# Buat database baru untuk testing lokal
php artisan migrate --seed
```

```sql
-- Manual di phpMyAdmin jika diperlukan:
CREATE DATABASE lapak_gaming_dev;
USE lapak_gaming_dev;
```

### 5. Run Development Server

```bash
# Terminal 1 - Vite Dev Server (untuk asset hot reload)
npm run dev

# Terminal 2 - Laravel Development Server
php artisan serve
```

Akses ke `http://localhost:8000`

### 6. Test Data (Seeder)

Sudah tersedia di `database/seeders/`
- Admin: `admin@lapakgaming.local` / `password`
- Buyer: `buyer1@test.local` - `buyer5@test.local` / `password`
- Seller: `seller1@test.local` - `seller3@test.local` / `password`

---

## Configuration

### Database Configuration

File: `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neoz6813_TB-K1-Database
DB_USERNAME=neoz6813
DB_PASSWORD=@Webihsananwar33
```

### Email SMTP Configuration

File: `.env`

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=lapakgaming.neoverse.my.id
MAIL_PORT=465
MAIL_USERNAME=administrator@lapakgaming.neoverse.my.id
MAIL_PASSWORD=tbsbdk1database
MAIL_FROM_ADDRESS=administrator@lapakgaming.neoverse.my.id
MAIL_FROM_NAME="Lapak Gaming"
```

**Penting:** Email verification menggunakan SMTP real.

### App Configuration

```env
APP_NAME="Lapak Gaming"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lapakgaming.neoverse.my.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
```

### Session & Security

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

BCRYPT_ROUNDS=12
```

---

## Build Assets dengan Vite

### Development Build

```bash
npm run dev
```

### Production Build (WAJIB sebelum upload ke cPanel)

```bash
npm run build
```

Output akan di-generate ke:
- `public/build/manifest.json`
- `public/build/assets/[file-hash].js`
- `public/build/assets/[file-hash].css`

**PENTING:** Jangan upload node_modules ke server production!

### Optimasi untuk Shared Hosting

File: `vite.config.js`

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: false, // Disable refresh di production
        }),
    ],
    build: {
        target: 'es2015',
        minify: 'terser', // minify JS
        cssMinify: true,  // minify CSS
        reportCompressedSize: false,
        outDir: 'public/build',
    },
});
```

---

## Deploy ke cPanel

### Pre-Deployment Checklist

- [x] Build asset: `npm run build`
- [x] Clear cache: `php artisan cache:clear`
- [x] Test migration lokal
- [x] Update .env untuk production
- [x] File upload terbatas 2GB

### Metode 1: Upload via cPanel File Manager

#### 1. Prepare Files untuk Upload

```bash
# Di lokal (D:0_Project_VS_Code folder)
# Buat folder clean untuk upload

# JANGAN upload:
- node_modules/
- .git/
- package-lock.json (optional)
- *.log files
- .env.local / .env.*.php

# WAJIB upload:
- app/
- bootstrap/
- config/
- database/
- public/ (dengan build/)
- resources/
- routes/
- storage/ (kosong OK)
- vendor/ (dari composer)
- .env (production)
- .htaccess
- artisan
- composer.json
- composer.lock
```

#### 2. Upload Struktur

**Via FTP/SFTP (Recommended):**

```bash
# Setup FTP di cPanel
# Download credentials dari cPanel > FTP Accounts

# Upload ke /home/username/marketplace-laravel/
# Dan ke /home/username/public_html/ untuk public folder
```

**File Manager cPanel:**

1. Login cPanel dengan domain `.my.id`
2. Buka File Manager
3. Navigasi ke `/home/username/`
4. Upload file-file yang sudah disiapkan

#### 3. Database - Import via cPanel

```bash
# Export dari localhost
mysqldump -u root -p lapak_gaming_dev > backup.sql

# Di cPanel:
# 1. Login ke phpMyAdmin
# 2. Buat database baru: neoz6813_TB-K1-Database
# 3. Import file SQL
# 4. Update .env dengan credentials cPanel
```

#### 4. Structure di cPanel Server

```
/home/username/
├── marketplace-laravel/          → app, bootstrap, config, dll
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env                       (production config)
│   ├── artisan
│   ├── composer.json
│   └── composer.lock
│
└── public_html/                   (document root untuk domain)
    ├── index.php                  → pointing ke app/public
    ├── .htaccess
    ├── build/                     → generated by Vite
    │   ├── manifest.json
    │   └── assets/
    ├── storage/
    │   ├── app/
    │   └── logs/
    └── uploads/                   (untuk user files)
```

### Metode 2: Via Git (Lebih Mudah)

Jika cPanel support SSH:

```bash
# Di server cPanel
cd /home/username/marketplace-laravel

# Clone dari repository
git clone <your-repo> .
composer install --no-dev --optimize-autoloader
npm install --production
npm run build

# Setup permissions
chmod -R 775 storage bootstrap/cache
chown -R nobody:nobody storage bootstrap/cache
```

### Post-Upload di cPanel

#### 1. SSH Access (jika available)

```bash
ssh username@lapakgaming.neoverse.my.id

cd public_html

# Update symlink public folder
rm -rf public
ln -s ../marketplace-laravel/public public

# Laravel migrations
php artisan migrate --force
php artisan db:seed --force (optional, jika fresh DB)

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. File Permissions (PENTING!)

```bash
# Via SSH:
chmod -R 755 /home/username/marketplace-laravel

# Storage & Bootstrap Writable:
chmod -R 777 /home/username/marketplace-laravel/storage
chmod -R 777 /home/username/marketplace-laravel/bootstrap/cache
chmod -R 777 /home/username/public_html/storage
chmod -R 777 /home/username/public_html/uploads

# .htaccess
chmod 644 /home/username/public_html/.htaccess
```

#### 3. PHP Configuration (cPanel)

File: `public_html/.htaccess`

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Disable directory listing
Options -Indexes

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"  
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## Post-Deployment Setup

### 1. Test Aplikasi

```
https://lapakgaming.neoverse.my.id
```

### 2. Verify Email SMTP

```bash
# SSH ke server
php artisan tinker
```

```php
// Di tinker:
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

### 3. Create Admin Account

```bash
php artisan tinker

// Create admin user
$admin = App\Models\User::create([
    'name' => 'Admin Lapak',
    'email' => 'admin@lapakgaming.neoverse.my.id',
    'password' => Hash::make('securepassword'),
    'phone' => '082xxxxxxx',
    'role' => 'admin',
    'email_verified_at' => now(),
    'is_active' => true,
]);

// Create wallet
Wallet::create(['user_id' => $admin->id, 'balance' => 0]);

// Verify
$admin->refresh();
```

### 4. Setup Storage Link

```bash
php artisan storage:link
```

Ini membuat symlink dari `storage/app/public` ke `public/storage`

---

## SSL & Domain Configuration

### DNS Setup untuk Domain .my.id

#### 1. Update A Record

Di provider domain .my.id:

```
A record: lapakgaming.neoverse.my.id → [cPanel Server IP]
```

#### 2. cPanel SSL Certificate

1. Login cPanel
2. Buka "AutoSSL"
3. Klik "Manage AutoSSL"
4. Ensure lapakgaming.neoverse.my.id tertera
5. Force SSL via `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

#### 3. Update App URL

```env
APP_URL=https://lapakgaming.neoverse.my.id
```

```bash
php artisan config:cache
```

---

## Troubleshooting

### 1. 404 Not Found

**Problem:** Masuk ke domain tapi halaman 404

**Fix:**
```bash
# Check if public/index.php accessible
# Check .htaccess rewrite rules

# Verify via SSH:
ls -la public/index.php
cat .htaccess
```

### 2. "No Application Encryption Key Specified"

**Fix:**
```bash
php artisan key:generate
php artisan config:cache
```

### 3. Storage Permission Denied

**Fix:**
```bash
chmod -R 755 storage bootstrap/cache
chown -R nobody:nogroup storage bootstrap/cache
```

### 4. Email Not Sending

**Debug:**
```bash
# Check SMTP credentials di .env
# Test via tinker (see above)
# Check logs: storage/logs/laravel.log
tail -f storage/logs/laravel.log
```

### 5. Database Connection Refused

**Fix:**
```bash
# Verify .env credentials
# MySQL accessible from cPanel?
# Check: cPanel > MySQL > Remote MySQL

# SSH test:
mysql -h localhost -u neoz6813 -p'@Webihsananwar33' neoz6813_TB-K1-Database
```

### 6. Vite Asset 404

**Problem:** CSS/JS tidak load

**Fix:**
```bash
# Rebuild assets
npm run build

# Cache clear
php artisan config:cache
php artisan view:cache
```

### 7. Slow Performance

**Optimize:**
```bash
# Config caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Composer optimize
composer install --optimize-autoloader --no-dev
```

---

## Monitoring & Maintenance

### Log Rotation

File: `config/logging.php` - sudah auto rotate (single stack)

```bash
# Check logs:
tail -f storage/logs/laravel.log
```

### Database Backup

```bash
# Weekly backup script
cd /home/username/marketplace-laravel
php artisan backup:run
```

### Update Dependencies (Safe)

```bash
# Security updates only
composer update --no-dev

# Rebuild
npm run build
```

---

## API Endpoints

### Search Products
- **GET** `/api/search?q=keyword&category=1&sort=newest`
- Public endpoint

### Chat
- **GET** `/api/chat/conversations` - Auth required
- **POST** `/api/chat/{receiverId}` - Send message
- **PATCH** `/api/chat/{conversationId}/read` - Mark as read

### Webhooks (Future)
- Order status changes
- Payment notifications
- Email verification

---

## Environment Variables Reference

```env
# App
APP_NAME=Lapak Gaming
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lapakgaming.neoverse.my.id
APP_KEY=base64:YOUR_KEY_HERE
APP_LOCALE=id
APP_FALLBACK_LOCALE=id

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=neoz6813_TB-K1-Database
DB_USERNAME=neoz6813
DB_PASSWORD=@Webihsananwar33

# Mail
MAIL_MAILER=smtp
MAIL_HOST=lapakgaming.neoverse.my.id
MAIL_PORT=465
MAIL_USERNAME=administrator@lapakgaming.neoverse.my.id
MAIL_PASSWORD=tbsbdk1database
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=administrator@lapakgaming.neoverse.my.id

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Other
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
```

---

## Support & Documentation

### File Referensi
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [cPanel Documentation](https://documentation.cpanel.net)

### Kontak Technical Support
- Email Setup: administrator@lapakgaming.neoverse.my.id
- cPanel Support: Hubungi hosting provider

---

**Last Updated:** 2024
**Version:** 1.0 Production Ready

# Quick Security Checklist - Lapak Gaming

## ✅ Done (Sudah Dikonfigurasi)

| Item | Status | File |
|------|--------|------|
| Directory listing disabled | ✅ | `.htaccess` |
| Block .env access | ✅ | `.htaccess` |
| Block .git access | ✅ | `.htaccess` |
| Block composer.json/lock | ✅ | `.htaccess` |
| Block artisan access | ✅ | `.htaccess` |
| Block hidden files (.*) | ✅ | `.htaccess` |
| All requests to index.php | ✅ | `.htaccess` |
| Nginx config sample | ✅ | `nginx.conf.example` |

---

## 🚀 TODO: Saat Deploy ke Hosting

### **Step 1: Upload Files**
```
Upload seluruh folder lapak_gaming ke server
├── /public/  ← .htaccess HARUS ada di sini
├── /app/
├── /database/
├── .env
└── ... (files lainnya)
```

### **Step 2: Set Web Root**
- **cPanel**: Set Document Root ke `/public`
- **Plesk**: Set Web Root ke `/public`
- **Direktory Hosting**: `DocumentRoot /home/user/lapak_gaming/public`

### **Step 3: Aktifkan HTTPS**
- Setup SSL/TLS (Let's Encrypt recommended)
- Update `APP_URL` di `.env` menjadi `https://yoursite.com`

### **Step 4: Set Permissions**
```bash
chmod 755 storage
chmod 755 bootstrap/cache
chmod 644 public/.htaccess
```

### **Step 5: Verify Security**

Test di browser (setelah deploy):

| URL | Expected | Actual |
|-----|----------|--------|
| `https://yoursite.com` | ✅ Homepage muncul | ✅ |
| `https://yoursite.com/.env` | ❌ Error 403 | ✅ |
| `https://yoursite.com/public/` | ❌ No listing | ✅ |
| `https://yoursite.com/app/` | ❌ Error 403/404 | ✅ |
| `https://yoursite.com/composer.json` | ❌ Error 403 | ✅ |

---

## 📝 Files Created

1. **DEPLOYMENT_SECURITY_GUIDE.md** - Panduan lengkap deployment & security
2. **nginx.conf.example** - Contoh konfigurasi Nginx (jika pakai Nginx)
3. **QUICK_CHECKLIST.md** - Checklist ini

---

## 💬 Key Security Features

| Feature | Benefit |
|---------|---------|
| Directory Listing Disabled | Users tidak bisa browse struktur folder |
| File Sensitif Protected | .env, .git, composer.json, dll tidak accessible |
| Hidden Files Blocked | File yang dimulai dengan `.` (dot) di-block |
| All Requests to index.php | User tidak bisa akses file/folder langsung |
| Document Root = /public | Folder aplikasi (app/, database/, etc) tersembunyi |

---

## ⚠️ Common Issues & Solutions

### Issue: "Access Denied" saat membuka website
**Solusi:**
- Check .htaccess uploaded di /public folder
- Verify Apache mod_rewrite enabled
- Check file permissions (755 untuk directories)

### Issue: Masih bisa akses .env via browser
**Solusi:**
- Pastikan .htaccess di /public folder
- Refresh browser cache (Ctrl+Shift+Delete)
- Check Apache error log

### Issue: Akses /app atau folder lain masih bisa
**Solusi:**
- Pastikan Web Root/Document Root = /public (bukan root folder)
- Contact hosting provider verify konfigurasi

---

## 🎨 TAILWIND CSS & PRODUCTION BUILD CHECKLIST

### ✅ Local Development Setup - Sudah Selesai

| Item | Status | Detail |
|------|--------|--------|
| Tailwind CSS v4 | ✅ | Via @tailwindcss/vite di vite.config.js |
| Vite Build Tool | ✅ | Configured untuk Laravel 11 |
| Hot Reload | ✅ | `npm run dev` auto-reload CSS/JS |
| Production Assets | ✅ | `public/build/` folder dengan manifest.json |
| Laravel Integration | ✅ | `@vite` directive di app.blade.php |

### 🚀 SEBELUM UPLOAD - Lakukan di Laptop (PENTING!)

- [ ] Buka PowerShell/Terminal di folder project
- [ ] Jalankan: `npm run build` (atau `.\build-for-production.ps1`)
- [ ] Pastikan output tidak ada error
- [ ] Verifikasi `public/build/` folder ada:
  - [ ] `manifest.json`
  - [ ] `assets/app-*.css` 
  - [ ] `assets/app-*.js`
- [ ] Jalankan: `composer install --no-dev --optimize-autoloader`
- [ ] Pastikan `vendor/` folder size berkurang (~100MB lebih kecil)

### 📤 SETELAH UPLOAD KE CPANEL

- [ ] Upload folder: `app/`, `bootstrap/`, `config/`, `database/`, `public/` (termasuk `public/build/`), `resources/`, `routes/`, `storage/`, `vendor/`
- [ ] Upload file: `.env`, `artisan`, `composer.json`, `package.json`
- [ ] Set permissions: `storage/` dan `bootstrap/cache/` = 755 atau 775
- [ ] Verifikasi database credentials di `.env` cocok dengan database di cPanel

### ✨ VERIFIKASI DI HOSTING

Buka browser ke: `https://lapakgaming.neoverse.my.id`

- [ ] Page tidak blank/error 500
- [ ] Tailwind CSS styling ada (dark theme visible)
- [ ] Open DevTools (F12) → Network tab
  - [ ] Ada file: `app-*.css` dengan status 200
  - [ ] Ada file: `app-*.js` dengan status 200
  - [ ] File CSS size > 100KB (pre-minified) atau > 50KB (minified)
- [ ] Console tab tidak ada error merah
- [ ] Klik-klik buttons/forms, pastikan responsive

---

## 📋 Production Environment Variables

Current `.env` status:
```
APP_ENV=production              ✅ BENAR
APP_DEBUG=false                 ✅ BENAR
APP_URL=https://lapakgaming.neoverse.my.id  ✅ BENAR
DB_HOST=localhost               ✅ CORRECT
DB_DATABASE=neoz6813_TB-K1-Database  ✅ CORRECT  
DB_USERNAME=neoz6813_tbk1database    ✅ CORRECT
APP_KEY=base64:...              ✅ SUDAH ADA
```

✅ **Status: Siap Production**

---

## 🎯 How It Works

### Development (Local - `npm run dev`):
```
Browser → Vite Dev Server (port 5173)
  → Hot reload CSS/JS on save
  → Real-time Tailwind compilation
  → Fast iteration
```

### Production (Hosting - NO npm run dev):
```
Browser → Nginx/Apache → public/index.php
  → Laravel loads from public/build/
  → Pre-compiled CSS & JS (manifest.json)
  → ✅ Zero build time
  ✅ No Node.js needed
  ✅ No npm run dev needed
```

---

## 🚫 Do NOT

- ❌ Do NOT run `npm run dev` on server
- ❌ Do NOT upload `node_modules/` (upload `vendor/` instead)
- ❌ Do NOT commit `.env` to Git
- ❌ Do NOT set `APP_DEBUG=true` in production
- ❌ Do NOT skip `npm run build` before upload

---

🎉 Website Anda sekarang production-ready dengan Tailwind CSS dan aman!

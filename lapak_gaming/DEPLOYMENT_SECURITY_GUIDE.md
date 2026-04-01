# Panduan Deployment & Security - Lapak Gaming

## 🔒 Keamanan Website

File `.htaccess` sudah dikonfigurasi untuk:
✅ Menonaktifkan directory listing (users tidak bisa melihat folder)
✅ Mengarahkan semua request ke `index.php`
✅ Memblokir akses file sensitif (.env, .git, composer.json, etc)
✅ Memblokir hidden files dan directories

---

## 📁 Konfigurasi Web Root yang Benar

### **PENTING: Saat Deploy, Atur Web Root ke folder `/public`**

Struktur folder yang aman:

```
/home/user/lapak_gaming/          ← ROOT FOLDER (tidak accessible dari web)
├── /public/                       ← WEB ROOT (hanya folder ini yang accessible)
│   ├── index.php
│   ├── .htaccess
│   └── build/
├── /app/                          ← TIDAK accessible
├── /database/                     ← TIDAK accessible
├── /resources/                    ← TIDAK accessible
├── /storage/                      ← TIDAK accessible
├── /routes/                       ← TIDAK accessible
├── .env                           ← TIDAK accessible (file konfigurasi sensitif)
├── composer.json                  ← TIDAK accessible
└── artisan                        ← TIDAK accessible
```

### **Contoh Konfigurasi cPanel/Hosting:**

1. **Document Root → `/public` (bukan root folder)**
2. Pastikan folder `/public` adalah yang di-expose ke internet

---

## 🔧 Konfigurasi Server

### **Untuk Apache (dengan mod_rewrite):**

✓ Sudah dikonfigurasi via `.htaccess`
✓ Pastikan `mod_rewrite` dan `mod_negotiation` enabled di server

### **Untuk Nginx:**

Tambahkan ke `nginx.conf` atau virtual host config:

```nginx
server {
    listen 80;
    server_name example.com www.example.com;
    
    # Set document root ke public folder
    root /home/user/lapak_gaming/public;
    
    # Menonaktifkan directory listing
    autoindex off;
    
    # Index file
    index index.php index.html index.htm;
    
    # Blokir akses file sensitif
    location ~ /\.env {
        deny all;
    }
    
    location ~ /\.git {
        deny all;
    }
    
    location ~ /composer\.(json|lock)$ {
        deny all;
    }
    
    location ~ /package\.(json|lock)$ {
        deny all;
    }
    
    # Pass PHP requests ke PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    
    # Rewrite untuk Laravel (semua request ke index.php)
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

---

## 🚀 Testing Keamanan Lokal

### **Test 1: Directory Listing (HARUS FAIL)**
```
URL: http://localhost/build/
❌ HARUS return error atau redirect (bukan list file)
```

### **Test 2: Akses .env (HARUS FAIL)**
```
URL: http://localhost/.env
❌ HARUS return 403 Forbidden
```

### **Test 3: Akses composer.json (HARUS FAIL)**
```
URL: http://localhost/composer.json
❌ HARUS return 403 Forbidden
```

### **Test 4: Direct Request Ke folder (HARUS REDIRECT)**
```
URL: http://localhost/api/
✅ HARUS redirect/handle via index.php
```

### **Test 5: Akses Main File (HARUS SUCCESS)**
```
URL: http://localhost/
URL: http://localhost/login
URL: http://localhost/dashboard
✅ HARUS membuka index.php dan aplikasi berjalan normal
```

---

## ✨ Checklist Deployment

Sebelum upload ke hosting:

- [ ] Document root menunjuk ke folder `/public`
- [ ] `.htaccess` sudah di-upload ke folder `/public`
- [ ] File `.env` ada di folder root (bukan di `/public`)
- [ ] Database credentials ada di `.env` (bukan hardcoded)
- [ ] `storage/` folder writable (chmod 775)
- [ ] `bootstrap/cache/` folder writable (chmod 775)
- [ ] Coba akses website → harus buka ke homepage
- [ ] Test akses `.env` → harus error 403
- [ ] Test akses direktori → harus tidak bisa list files

---

## 🛡️ Security Best Practices Tambahan

### Di `.env` (file root, bukan public):
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourwebsite.com
DB_HOST=localhost
DB_USERNAME=your_db_user
DB_PASSWORD=secure_password_here
```

### Permission File Yang Tepat:
```bash
# Di server terminal:
chmod 755 /home/user/lapak_gaming
chmod 755 /home/user/lapak_gaming/public
chmod 755 /home/user/lapak_gaming/storage
chmod 755 /home/user/lapak_gaming/bootstrap/cache
chmod 644 /home/user/lapak_gaming/public/.htaccess
```

---

## 📞 Troubleshooting

**Q: Akses folder masih bisa buka directory listing?**
- A: Pastikan mod_rewrite enabled di Apache
- A: Check hosting support Apache dengan mod_rewrite/mod_negotiation

**Q: Website blank/error 500?**
- A: Check `storage/logs/laravel.log` for errors
- A: Ensure permissions correct (writable)

**Q: File .env bisa diakses via browser?**
- A: Check `.htaccess` di `public/` folder sudah ter-upload
- A: Atau gunakan nginx config jika using Nginx

---

💡 **Catatan**: Konfigurasi ini mengikuti Laravel best practices dan sudah production-ready!

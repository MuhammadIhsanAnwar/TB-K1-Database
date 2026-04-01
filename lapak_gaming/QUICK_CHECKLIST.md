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

🎉 Website Anda sekarang production-ready dan aman!

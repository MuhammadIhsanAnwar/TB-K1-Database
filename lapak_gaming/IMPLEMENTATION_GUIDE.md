# Email Verification & Buyer/Seller Separation Implementation

## Perubahan yang Dilakukan

### 1. Email Verification Requirement ✅

#### File: `app/Http/Controllers/AuthController.php`

**Perubahan:**
- **Login**: Sebelum user login, sistem akan cek apakah email sudah diverifikasi
- **Registration**: Setelah registrasi, user TIDAK akan langsung login tetapi diarahkan ke halaman login dengan pesan untuk verifikasi email
- User yang email-nya belum terverifikasi akan melihat pesan: "Silakan verifikasi email Anda terlebih dahulu sebelum login."

**Behavior:**
```
1. User mendaftar → Mendapat email verifikasi
2. User mencoba login sebelum verifikasi → Ditolak dengan pesan error
3. User verifikasi email via link di email
4. User bisa login setelah verifikasi
```

---

### 2. Pisahkan Tabel Buyers dan Sellers ✅

#### File: `database/migrations/2026_05_06_000002_create_buyers_and_sellers_tables.php`

**Tabel Baru:**

**`buyers` table:**
```sql
- id (Primary Key)
- name (string)
- username (string, unique)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- phone (string, nullable)
- avatar (string, nullable)
- status (enum: active, pending, suspended)
- suspended_at (timestamp, nullable)
- remember_token (string)
- created_at, updated_at (timestamps)
```

**`sellers` table:**
```sql
- id (Primary Key)
- name (string)
- username (string, unique)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- phone (string, nullable)
- avatar (string, nullable)
- seller_level_id (foreign key, nullable)
- status (enum: active, pending, suspended)
- suspended_at (timestamp, nullable)
- remember_token (string)
- created_at, updated_at (timestamps)
```

**Tabel Users:**
- Menambahkan kolom `user_type` (enum: buyer, seller, mixed) untuk backward compatibility

---

### 3. Model Baru ✅

#### `app/Models/Buyer.php`
- Implements `MustVerifyEmail` interface
- Relationships dengan: orders, reviews, cart, addresses, notifications
- Scopes: `active()`, `verified()`

#### `app/Models/Seller.php`
- Implements `MustVerifyEmail` interface
- Relationships dengan: level, products, orders, reviews, addresses, notifications
- Scopes: `active()`, `verified()`

---

### 4. Factory dan Seeder ✅

#### `database/factories/BuyerFactory.php`
- Generate buyer data dengan password default: `Password123!`
- Methods: `unverified()`, `suspended()`

#### `database/factories/SellerFactory.php`
- Generate seller data dengan password default: `Password123!`
- Default seller_level_id: 1 (Starter)
- Methods: `unverified()`, `suspended()`

#### `database/seeders/BuyerSeeder.php`

**Data yang di-seed:**
- 10 verified buyers (random)
- 5 unverified buyers (pending verification)
- 3 sample buyers dengan data spesifik:
  1. Ahmad Pembeli (verified)
  2. Siti Pembeli (verified)
  3. Budi Pembeli (unverified)

---

## Cara Menggunakan

### 1. Jalankan Migration

```bash
php artisan migrate
```

Atau jika ingin rollback sebelumnya:
```bash
php artisan migrate:refresh
```

### 2. Jalankan Seeder

**Option A: Jalankan semua seeder (termasuk BuyerSeeder)**
```bash
php artisan db:seed
```

**Option B: Jalankan hanya BuyerSeeder**
```bash
php artisan db:seed --class=BuyerSeeder
```

---

## Testing Email Verification

### Test Lokal (dengan SMTP Fake)

Edit `.env`:
```env
MAIL_MAILER=log
# atau
MAIL_MAILER=array
```

Setelah registrasi, cek file `storage/logs/laravel.log` atau `storage/mail` untuk melihat verification link.

### Test Manual

1. Register user baru
2. Cek halaman login dengan user belum verifikasi → Akan error
3. Buka verification email dan klik link
4. Coba login lagi → Berhasil

---

## Login Credentials untuk Testing

### Buyers (dari BuyerSeeder):
- Email: `ahmad@example.com` | Password: `Password123!` (verified)
- Email: `siti@example.com` | Password: `Password123!` (verified)
- Email: `budi@example.com` | Password: `Password123!` (unverified - tidak bisa login)

### Buyers Random:
- Password: `Password123!` untuk semua
- Cek database untuk melihat email address

---

## Catatan Penting

⚠️ **Backward Compatibility:**
- Tabel `users` masih ada dan tetap digunakan untuk admin dan demo accounts
- Kolom `user_type` menunjukkan apakah user adalah buyer, seller, atau mixed
- Aplikasi masih support autentikasi dari tabel `users` untuk backward compatibility

✅ **Going Forward:**
- User baru harus mendaftar sebagai Buyer atau Seller
- Mereka akan disimpan di tabel `buyers` atau `sellers` masing-masing
- Email verification wajib sebelum login

---

## Database Schema Relationship

```
users (legacy)
├── id, name, email, role (admin/buyer/seller)
├── user_type (buyer/seller/mixed)
└── email_verified_at

buyers (new)
├── id, name, username, email
├── email_verified_at (required untuk login)
├── phone, avatar
└── status (active/pending/suspended)

sellers (new)
├── id, name, username, email
├── email_verified_at (required untuk login)
├── phone, avatar, seller_level_id
└── status (active/pending/suspended)
```

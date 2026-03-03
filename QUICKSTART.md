# 🚀 Quick Start Guide - Lapak Gaming

## Panduan Instalasi Cepat

### 1. Persiapan
Pastikan terinstal:
- Node.js (v16+)
- MySQL (v8.0+)
- npm atau yarn

### 2. Setup Backend

```bash
# Masuk ke folder backend
cd backend

# Install dependencies
npm install

# Jalankan migrasi database (otomatis buat tabel)
npm run migrate

# Jalankan server
npm run dev
```

Server backend akan berjalan di: `http://localhost:5000`

### 3. Setup Frontend

```bash
# Buka terminal baru, masuk ke folder frontend
cd frontend

# Install dependencies
npm install

# Jalankan aplikasi
npm run dev
```

Frontend akan berjalan di: `http://localhost:3000`

## ✅ Akun Default

### Admin
- Email: `admin@lapakgaming.neoverse.my.id`
- Password: `admin123`

### Testing
Buat akun baru untuk buyer/seller melalui halaman register.
Email verifikasi akan dikirim otomatis.

## 📖 Fitur Utama

### Sebagai Buyer (Pembeli)
1. Browse produk dan kategori
2. Tambah ke keranjang
3. Checkout dan upload bukti bayar
4. Chat dengan seller
5. Beri rating & review
6. Kelola wallet

### Sebagai Seller (Penjual)
1. Tambah produk digital
2. Atur stok dan harga
3. Terima pesanan
4. Auto-delivery untuk produk digital
5. Withdraw saldo
6. Lihat analitik penjualan

### Sebagai Admin
1. Dashboard analytics lengkap
2. Moderasi produk
3. Manajemen user
4. Approve deposit/withdrawal
5. Handle dispute

## 🔧 Konfigurasi (Sudah di-setup)

### Database
- Host: localhost
- User: neoz6813
- Password: @Webihsananwar33
- Database: neoz6813_TB-K1-Database

### Email (SMTP)
- Host: lapakgaming.neoverse.my.id
- Port: 465
- User: administrator@lapakgaming.neoverse.my.id
- Password: tbsbdk1database

## 📚 API Documentation

### Authentication Endpoints
```
POST /api/auth/register     - Daftar akun baru
POST /api/auth/login        - Login
POST /api/auth/verify-email - Verifikasi email
POST /api/auth/logout       - Logout
```

### Product Endpoints
```
GET    /api/products           - List produk
GET    /api/products/:id       - Detail produk
POST   /api/products           - Buat produk (seller)
PUT    /api/products/:id       - Update produk
DELETE /api/products/:id       - Hapus produk
```

### Order Endpoints
```
POST /api/orders                    - Buat order
GET  /api/orders/buyer/my-orders    - Order buyer
GET  /api/orders/seller/my-orders   - Order seller
PUT  /api/orders/:id/confirm        - Konfirmasi order
```

### Wallet Endpoints
```
GET  /api/wallet              - Info wallet
GET  /api/wallet/transactions - Riwayat transaksi
POST /api/wallet/deposit      - Request deposit
POST /api/wallet/withdraw     - Request withdraw
```

## 🎯 Alur Transaksi

1. **Buyer** membuat order
2. Platform hold dana di escrow
3. **Seller** proses dan kirim item
4. **Buyer** konfirmasi terima item
5. Dana diteruskan ke wallet **Seller**
6. Platform ambil fee (default 5%)

## 💬 Chat Real-time

Menggunakan WebSocket untuk komunikasi:
- Chat buyer dengan seller
- Real-time messaging
- Typing indicator
- Read receipts

## 🎨 Theme

Aplikasi support dark/light mode:
- Toggle di navbar
- Otomatis save preference
- Responsive untuk mobile

## ⚡ Tips Development

### Hot Reload
Kedua server support hot reload:
- Backend: nodemon
- Frontend: Next.js dev mode

### Debug
```bash
# Backend logs
npm run dev

# Frontend logs
npm run dev
```

### Build Production
```bash
# Backend
npm start

# Frontend
npm run build && npm start
```

## 🐛 Troubleshooting

### Port sudah digunakan
```bash
# Ganti port di .env backend
PORT=5001

# Ganti port frontend
npm run dev -- -p 3001
```

### Database error
```bash
# Reset database
npm run migrate
```

### Email tidak terkirim
- Cek koneksi internet
- Verify SMTP credentials
- Cek spam folder

## 📞 Support

Jika ada error atau pertanyaan:
1. Cek console browser (F12)
2. Cek terminal backend
3. Lihat error message
4. Baca dokumentasi README.md

## 🎊 Selamat Mencoba!

Website marketplace digital siap digunakan. Silakan explore semua fitur yang tersedia!

---

**Made with ❤️ for TB-K1-Database Project**

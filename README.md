# Lapak Gaming - Digital Marketplace Platform

## 🎮 Production-Ready Digital Marketplace for Gaming Products

A complete, modern, and secure marketplace platform for buying and selling digital gaming products (game accounts, vouchers, items, top-up services) built with PHP 8+ OOP MVC architecture, fully compatible with cPanel shared hosting.

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [API Documentation](#api-documentation)
- [Escrow System](#escrow-system)
- [Security Features](#security-features)
- [Folder Structure](#folder-structure)
- [Default Credentials](#default-credentials)

---

## ✨ Features

### Multi-Role System
- **Guest**: Browse products, search, view categories
- **Buyer**: Purchase, chat, wallet, order tracking, reviews
- **Seller**: Product management, auto-delivery, sales analytics, withdrawals
- **Admin**: User management, moderation, dispute resolution, analytics

### Core Functionality
✅ **Escrow Payment System** - Secure transactions with automated release  
✅ **Internal Wallet** - Deposit, withdraw, transaction ledger  
✅ **Real-time Chat** - AJAX polling (no WebSocket needed)  
✅ **Email Notifications** - SMTP integration with PHPMailer  
✅ **Product Categories** - Multi-level hierarchy  
✅ **Advanced Search** - Real-time AJAX search  
✅ **Review & Rating System** - Buyer feedback with seller responses  
✅ **Seller Levels** - Bronze, Silver, Gold, Platinum (auto-calculated)  
✅ **Notification System** - Real-time polling for updates  
✅ **Responsive Design** - TailwindCSS mobile-first UI  
✅ **Dark/Light Mode** - User preference toggle  

### Security Features
🔒 JWT Authentication with refresh tokens  
🔒 Password hashing (bcrypt)  
🔒 PDO Prepared Statements (SQL injection prevention)  
🔒 XSS Protection & Input Sanitization  
🔒 CSRF Token Validation  
🔒 Rate Limiting (login attempts lockout)  
🔒 Email Verification (mandatory before login)  
🔒 Password Reset with expiring tokens  
🔒 Role-based Access Control (RBAC)  

---

## 🛠 Tech Stack

**Backend:**
- PHP 8+ (OOP MVC)
- MySQL (with PDO)
- Custom Lightweight JWT Library

**Frontend:**
- HTML5
- TailwindCSS (CDN)
- Vanilla JavaScript (ES6+ modular)
- Font Awesome Icons

**Email:**
- PHP mail() with SMTP configuration
- Custom EmailService class

**Hosting:**
- cPanel Shared Hosting Compatible
- No Node.js runtime required
- Standard Apache with mod_rewrite

---

## 📦 Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite enabled
- cPanel access (for production)

### Step 1: Upload Files

Upload the entire project to your cPanel public_html or subdirectory:

```
public_html/
├── TB-K1-Database/
│   ├── app/
│   ├── config/
│   ├── public/
│   ├── routes/
│   ├── vendor/
│   └── .htaccess
```

### Step 2: Set Document Root

In cPanel, set your document root to:
```
/home/username/public_html/TB-K1-Database/public
```

Or update `.htaccess` rewrite rules if in subdirectory.

---

## 🗄 Database Setup

### Create Database

1. Login to cPanel → MySQL Databases
2. Create database: `neoz6813_TB-K1-Database`
3. Create user: `neoz6813`
4. Set password: `@Webihsananwar33`
5. Grant ALL PRIVILEGES to user on database

### Import Schema

1. Open phpMyAdmin
2. Select the database
3. Import file: `database.sql`

This will create all tables and seed initial data including:
- Admin user
- Demo seller account
- Demo buyer account
- Sample categories
- Sample products

---

## ⚙ Configuration

### Database Configuration

Edit `config/database.php`:

```php
return [
    'host' => 'localhost',
    'database' => 'neoz6813_TB-K1-Database',
    'username' => 'neoz6813',
    'password' => '@Webihsananwar33',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
```

### Email Configuration

Edit `config/mail.php`:

```php
return [
    'host' => 'lapakgaming.neoverse.my.id',
    'username' => 'administrator@lapakgaming.neoverse.my.id',
    'password' => 'tbsbdk1database',
    'port' => 465,
    'encryption' => 'ssl',
];
```

### Application Settings

Edit `config/app.php`:

```php
return [
    'name' => 'Lapak Gaming Marketplace',
    'url' => 'https://lapakgaming.neoverse.my.id',
    'jwt_secret' => 'your-secret-key-change-this-in-production-2026',
    'platform_fee_percentage' => 5, // 5% commission
];
```

**⚠️ IMPORTANT:** Change `jwt_secret` in production!

---

## 📚 API Documentation

### Authentication Endpoints

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
  "email": "user@example.com",
  "username": "johndoe",
  "password": "SecurePass123",
  "full_name": "John Doe",
  "phone": "081234567890",
  "role": "buyer" // or "seller"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "SecurePass123"
}

Response:
{
  "success": true,
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "refresh_token": "abc123...",
    "user": {
      "id": 1,
      "email": "user@example.com",
      "username": "johndoe",
      "role": "buyer"
    }
  }
}
```

#### Verify Email
```http
POST /api/auth/verify-email?token=verification_token_here
```

### Product Endpoints

#### Get Products
```http
GET /api/products?page=1&limit=20
```

#### Search Products
```http
GET /api/products/search?q=mobile+legends
```

#### Create Product (Seller)
```http
POST /api/products
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "name": "Mobile Legends Mythic Account",
  "description": "800+ points, 150 skins",
  "price": 1500000,
  "discount_price": 1350000,
  "category_id": 6,
  "product_type": "account",
  "delivery_method": "manual",
  "stock_quantity": 1
}
```

### Order Endpoints (Escrow System)

#### Create Order
```http
POST /api/orders
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 1,
  "payment_method": "wallet",
  "notes": "Please deliver to my email"
}
```

#### Deliver Order (Seller)
```http
POST /api/orders/{orderId}/deliver
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "digital_items": {
    "email": "account@example.com",
    "password": "AccountPassword123",
    "details": "Additional information"
  }
}
```

#### Confirm Delivery (Buyer)
```http
POST /api/orders/{orderId}/confirm
Authorization: Bearer {access_token}
```
*This releases payment from escrow to seller*

### Wallet Endpoints

#### Get Balance
```http
GET /api/wallet
Authorization: Bearer {access_token}
```

#### Deposit
```http
POST /api/wallet/deposit
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "amount": 100000,
  "payment_method": "bank_transfer"
}
```

#### Withdraw
```http
POST /api/wallet/withdraw
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "amount": 500000,
  "bank_name": "BCA",
  "account_number": "1234567890",
  "account_name": "John Doe"
}
```

---

## 💰 Escrow System Flow

### 1. Order Creation
```
Buyer clicks "Buy Now" → System creates order with status: pending_payment
```

### 2. Payment
```
IF payment_method = "wallet":
  ├─ Deduct from buyer wallet
  ├─ Add to seller pending_balance (ESCROW)
  └─ Update order status: processing
```

### 3. Delivery
```
Seller delivers digital items
├─ Order status: delivered
├─ Buyer receives notification
└─ Product sold_count incremented
```

### 4. Confirmation (Payment Release)
```
Buyer confirms delivery
├─ Transfer from seller pending_balance to balance
├─ Record transaction in wallet_transactions
├─ Order status: completed
├─ Platform fee deducted automatically
├─ Seller total_sales incremented
└─ Seller level recalculated
```

### 5. Dispute (Optional)
```
Buyer raises dispute
├─ Order status: disputed
├─ Admin notified
├─ Escrow funds held
└─ Admin can resolve (refund or release)
```

---

## 🔐 Security Features

### Authentication
- **JWT Tokens**: Access token (1 hour) + Refresh token (7 days)
- **Password Hashing**: bcrypt with cost factor 10
- **Email Verification**: Mandatory before first login
- **Account Lockout**: 5 failed attempts = 15 minute lock

### Input Validation
- **PDO Prepared Statements**: All database queries
- **XSS Protection**: HTML sanitization on all inputs
- **CSRF Tokens**: Form validation (ready to implement)
- **SQL Injection**: Prevented via PDO

### File Upload (Future Enhancement)
- MIME type checking
- File size limits
- Sanitized filenames
- Restricted extensions

---

## 📁 Folder Structure

```
TB-K1-Database/
├── app/
│   ├── Controllers/         # Controllers (AuthController, OrderController, etc.)
│   ├── Core/               # Framework core (Database, Router, Model, Controller)
│   ├── Helpers/            # Helper functions (JWT, Security, Response, String)
│   ├── Middleware/         # Middleware (Auth, Role, CSRF, Guest)
│   ├── Models/             # Database models (User, Product, Order, Wallet, etc.)
│   ├── Services/           # Business logic services (EmailService)
│   └── views/              # HTML templates (home, login, dashboard)
│       ├── auth/           # Authentication pages
│       ├── products/       # Product pages
│       ├── buyer/          # Buyer dashboard
│       ├── seller/         # Seller dashboard
│       ├── chat/           # Chat interface
│       └── dashboard/      # Main dashboard
├── config/
│   ├── app.php            # Application configuration
│   ├── database.php       # Database credentials
│   └── mail.php           # Email SMTP settings
├── public/
│   ├── assets/
│   │   ├── css/           # Custom stylesheets
│   │   ├── js/            # JavaScript files (app.js)
│   │   └── img/           # Images
│   ├── index.php          # Application entry point
│   └── .htaccess          # Apache rewrite rules
├── routes/
│   ├── api.php            # API routes
│   └── web.php            # Web routes
├── vendor/
│   └── firebase-jwt.php   # Custom JWT implementation
├── database.sql           # Database schema + seed data
├── .htaccess             # Root htaccess
└── README.md             # This file
```

---

## 🔑 Default Credentials

### Admin Account
```
Email: admin@lapakgaming.neoverse.my.id
Username: admin
Password: password
```

### Demo Seller
```
Email: seller@demo.com
Username: demo_seller
Password: password
Wallet Balance: Rp 500,000
```

### Demo Buyer
```
Email: buyer@demo.com
Username: demo_buyer
Password: password
Wallet Balance: Rp 1,000,000
```

**⚠️ CHANGE ALL DEFAULT PASSWORDS IN PRODUCTION!**

---

## 🚀 Deployment Guide

### cPanel Deployment

1. **Upload Files**: Via File Manager or FTP to `/public_html/TB-K1-Database/`
2. **Create Database**: MySQL Database in cPanel
3. **Import Schema**: phpMyAdmin → Import `database.sql`
4. **Update Config**: Edit `config/database.php` with cPanel credentials
5. **Set Document Root**: Point to `/public_html/TB-K1-Database/public`
6. **Test Email**: Send test email via SMTP settings
7. **Update JWT Secret**: Change in `config/app.php`

### SSL Certificate (Recommended)
Enable Let's Encrypt SSL in cPanel for HTTPS

---

## 🧪 Testing the System

### Test Buyer Flow
1. Register as buyer
2. Verify email
3. Login
4. Add funds to wallet (`/api/wallet/deposit`)
5. Browse products
6. Purchase product with wallet
7. Receive delivery
8. Confirm order (releases escrow)
9. Leave review

### Test Seller Flow
1. Register as seller
2. Verify email
3. Login
4. Create product
5. Receive order notification
6. Deliver digital items
7. Wait for buyer confirmation
8. Check wallet for earnings
9. Withdraw funds

### Test Chat System
1. Login as buyer
2. Navigate to `/chat`
3. Send message to seller
4. Login as seller
5. Receive message (AJAX polling every 3s)
6. Reply to buyer

---

## 📊 Platform Fees

Default commission: **5%** of subtotal

Example:
- Product Price: Rp 1,000,000
- Platform Fee: Rp 50,000
- Seller Receives: Rp 950,000
- Buyer Pays: Rp 1,050,000

Configure in `config/app.php`:
```php
'platform_fee_percentage' => 5
```

---

## 🎖 Seller Levels

Automatically calculated based on total sales:

| Level        | Sales Range      | Badge Color |
|-------------|------------------|-------------|
| **Bronze**  | 0 - 50 sales     | Orange      |
| **Silver**  | 51 - 200 sales   | Gray        |
| **Gold**    | 201 - 500 sales  | Yellow      |
| **Platinum**| 501+ sales       | Purple      |

---

## 🔧 Troubleshooting

### Issue: 404 on all pages
**Solution**: Enable mod_rewrite in Apache, check .htaccess

### Issue: Database connection failed
**Solution**: Verify credentials in `config/database.php`

### Issue: Email not sending
**Solution**: Check SMTP settings in `config/mail.php`, verify port 465

### Issue: JWT decode errors
**Solution**: Clear browser localStorage, generate new tokens

### Issue: Blank screen / PHP errors
**Solution**: Check PHP error logs, ensure PHP 8.0+

---

## 📝 License

This project is proprietary software for educational purposes.

---

## 🤝 Support

For issues or questions:
- Email: administrator@lapakgaming.neoverse.my.id
- Documentation: See inline code comments

---

## 🎯 Roadmap / Future Enhancements

- [ ] Payment Gateway Integration (Midtrans, Xendit)
- [ ] PDF Invoice Generation
- [ ] Advanced Analytics Dashboard
- [ ] Multi-currency Support
- [ ] API Rate Limiting
- [ ] Redis Caching
- [ ] WebSocket for Real-time Chat
- [ ] Mobile App API
- [ ] Two-Factor Authentication (2FA)
- [ ] Affiliate System

---

**Built with ❤️ for Digital Gaming Commerce**

Last Updated: March 4, 2026

# 🎮 Lapak Gaming - Digital Marketplace Platform

**Production-Ready Digital Marketplace untuk Game Items, Vouchers, Accounts, & Top-Up Services**

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-green.svg)
![Laravel](https://img.shields.io/badge/Laravel-11+-red.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)

> Platform marketplace digital modern dengan sistem escrow, wallet internal, & AJAX chat polling yang compatible dengan shared hosting cPanel tanpa Node.js runtime di production.

## 📋 Daftar Isi

- [Features](#features)
- [Tech Stack](#tech-stack) - [Quick Start](#quick-start)
- [Deployment](#deployment)
- [Support](#support)

## ✨ Features

✅ **Guest Features** - Homepage, browse, search suggestions, register email verified
✅ **Buyer Features** - Checkout, wallet, order tracking, AJAX chat (3s polling), reviews, invoice PDF
✅ **Seller Features** - Dashboard, product CRUD, analytics, earnings, withdrawal, level system
✅ **Admin Features** - User management, product moderation, dispute resolution
✅ **Escrow System** - Dynamic order status (pending → completed)
✅ **Wallet System** - Internal balance, transaction ledger, hold mechanism
✅ **Chat System** - AJAX polling every 3 seconds (no WebSocket)
✅ **Email System** - SMTP via cPanel + verification tokens
✅ **Security** - CSRF, XSS sanitization, rate limiting, role middleware

## 🛠 Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | Laravel 11, PHP 8.2+ |
| Frontend | Blade, TailwindCSS, Alpine.js |
| Database | MySQL 8.0+ |
| Build Tool | Vite (dev only) |
| Hosting | cPanel shared hosting |

## 📦 Requirements

- PHP 8.2+ (OpenSSL, PDO_MySQL, Mbstring)
- Composer 2.4+
- MySQL 8.0+
- Node.js 18+ (development only)

## 🚀 Quick Start

### Development Setup

```bash
cd lapak_gaming

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed

# Build assets
npm run build

# Run server (2 terminals)
npm run dev          # Terminal 1
php artisan serve    # Terminal 2
```

**Test Credentials:**
- Admin: `admin@lapakgaming.local` / `password`
- Buyer: `buyer1@test.local` / `password`
- Seller: `seller1@test.local` / `password`

### Project Structure

```
app/
├── Http/Controllers/    # Controllers (Auth, Seller, Admin, API)
├── Models/              # Eloquent models (User, Product, Order, etc)
└── Middleware/          # Auth & role-based middleware

database/
├── migrations/          # Complete schema
└── seeders/             # Test data

resources/
├── views/               # Blade templates
├── css/                 # TailwindCSS
└── js/                  # Alpine.js

routes/
├── web.php              # Web routes
└── api.php              # API endpoints
```

## 💾 Key Database Models

- **User** - Buyer, Seller, Admin roles
- **Product** - Digital products with stock
- **Order** - Escrow tracking with multiple statuses
- **Wallet** - Internal balance + hold_balance
- **Message** - AJAX chat system
- **SellerAccount** - Extended seller profile with level
- **Review** - Product & seller ratings

## 🚀 Deployment to cPanel

**Quick Steps:**
1. `npm run build` - Generate assets
2. Upload files to cPanel (FTP/File Manager)
3. Import database via phpMyAdmin
4. Update `.env` with production credentials
5. Run `php artisan migrate --force`

**DETAILED GUIDE:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

### Key Deployment Points

✅ Structure: App in `/home/username/lapak_gaming/`, public in `/public_html/`
✅ Permissions: `chmod 755` app, `chmod 777` storage
✅ SSL: cPanel AutoSSL enabled
✅ Email: SMTP configured via `.env`
✅ No Node.js needed at runtime (only build time)

## 🔐 Security

- CSRF protection (tokens in forms)
- Password hashing (bcrypt 12 rounds)
- Email verification (24hr token)
- Rate limiting (login attempts)
- XSS sanitization (Blade auto-escape)
- SQL injection prevention (prepared statements)
- File upload validation (MIME type)
- Role-based access control (middleware)

## 📊 Escrow Flow

```
Buyer Checkout
    ↓ (payment_method: wallet/bank)
Deduct Wallet / Await Payment Proof
    ↓
Seller Accept & Deliver
    ↓
Buyer Confirm Receipt
    ↓
Transfer to Seller Wallet (subtotal - commission)
    ↓
Completed (can review)
```

## 💬 Chat System

- AJAX polling every 3 seconds
- Unread message counter
- Conversation grouping
- Order context support
- No WebSocket (cPanel compatible)

## 🔗 API Endpoints

### Public
- `GET /api/search?q=keyword&sort=newest` - Search products
- `GET /api/search/suggestions?q=keyword` - Search autocomplete

### Authenticated
- `GET /api/chat/conversations` - List chat
- `GET /api/chat/{id}` - Get messages
- `POST /api/chat/{receiverId}` - Send message
- `PUT /api/chat/{id}/read` - Mark read

## 📝 Environment Variables

```env
APP_NAME=Lapak Gaming
APP_URL=https://lapakgaming.neoverse.my.id
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_DATABASE=neoz6813_TB-K1-Database
DB_USERNAME=neoz6813
DB_PASSWORD=@Webihsananwar33

MAIL_HOST=lapakgaming.neoverse.my.id
MAIL_PORT=465
MAIL_USERNAME=administrator@lapakgaming.neoverse.my.id
MAIL_PASSWORD=tbsbdk1database
```

[See DEPLOYMENT_GUIDE.md for complete .env reference]

## 🧪 Testing

```bash
php artisan migrate --seed              # Fresh data
php artisan test                        # Run tests
php artisan tinker                      # Interact with app
```

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| 500 Error | Check `storage/logs/laravel.log` |
| DB Connection | Verify `.env`, check MySQL running |
| Email not sending | Test SMTP in tinker, check logs |
| Assets 404 | `npm run build` + clear cache |
| Permissions | `chmod 755 app`, `chmod 777 storage` |

[Full troubleshooting: DEPLOYMENT_GUIDE.md#troubleshooting]

## 📚 Documentation

- **Setup & Features:** This README
- **Deployment:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- **Laravel Docs:** https://laravel.com/docs
- **Tailwind:** https://tailwindcss.com
- **Alpine:** https://alpinejs.dev

## 📦 File Uploads

- Product thumbnail: `storage/app/public/products/`
- Payment proof: `storage/app/public/payment-proofs/`
- Max size: 2MB (configurable)

## 🔄 Background Jobs & Scheduling

Currently using `QUEUE_CONNECTION=sync` for simplicity. Can upgrade to Redis/database queues for production scaling.

## 🎯 Seller Levels

| Level | Min Sales | Min Rating | Commission |
|-------|-----------|-----------|------------|
| Regular | 0 | 0 ⭐ | 15% |
| Gold | 50 | 4.0 ⭐ | 12% |
| Platinum | 200 | 4.5 ⭐ | 10% |

Auto-calculated based on seller stats.

## 🛡️ Rate Limiting

- Login: 5 attempts per 15 minutes
- API: Standard Laravel rate limiting
- Configurable per-route

## 🌍 Localization

- Default: Bahasa Indonesia (id)
- Fallback: English
- Easy to add more languages

## 🎨 Responsive Design

- Mobile-first with Tailwind
- Tested on: Desktop, Tablet, Mobile
- Dark mode support

## ⚡ Performance

- Query optimization (eager loading)
- Database indexing (important fields)
- Asset minification via Vite
- Caching: config, routes, views
- Lazy loading: images

## 🚫 Limitations & Notes

❌ **Not included:**
- Real payment gateway (use simulation setup)
- SMS notifications (email only)
- Advanced e-signature for disputes
- Blockchain/NFT integration
- Multiple currency support

✅ **Easy to upgrade:**
- Add payment gateways (Midtrans, Stripe)
- SMS notifications (Twilio)
- WebSocket chat (Laravel Reverb)
- Cron jobs (Laravel Scheduler)

## 📄 License

MIT License - Free for commercial & personal use

## 👥 Support

- 📧 Issues & Questions: Check this README & DEPLOYMENT_GUIDE.md first
- 📚 Learning: Read inline code comments
- 🐛 Bugs: Review error logs in `storage/logs/`

## 🙏 Credits

Built with:
- Laravel Framework
- Tailwind CSS
- Alpine.js
- MySQL
- cPanel

---

**Production Ready | v1.0 | 2024**

For detailed deployment steps → **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)**

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

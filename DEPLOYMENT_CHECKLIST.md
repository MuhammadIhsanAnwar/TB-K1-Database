# 🚀 Production Deployment Checklist

## Pre-Deployment Tasks

### ✅ Security Configuration

- [ ] **Change JWT Secret** - Update `JWT_SECRET` in `config/app.php` (use random 64-char string)
  ```php
  'jwt_secret' => 'GENERATE_NEW_RANDOM_SECRET_HERE_64_CHARACTERS_MINIMUM',
  ```

- [ ] **Update Default Passwords** - Change all demo account passwords:
  ```sql
  -- Login to phpMyAdmin and run:
  DELETE FROM users WHERE email IN ('admin@lapakgaming.neoverse.my.id', 'seller@demo.com', 'buyer@demo.com');
  ```

- [ ] **CSRF Token Generation** - Verify `config/app.php` CSRF settings are enabled

- [ ] **Database Credentials** - Double-check `config/database.php` matches your cPanel MySQL:
  ```php
  'host' => 'localhost',
  'database' => 'neoz6813_TB-K1-Database',
  'username' => 'neoz6813_TB-K1-Database',
  'password' => 'your_database_password',
  ```

- [ ] **Email Configuration** - Verify SMTP settings in `config/mail.php`:
  ```php
  'smtp_host' => 'lapakgaming.neoverse.my.id',
  'smtp_port' => 465,
  'smtp_username' => 'noreply@lapakgaming.neoverse.my.id',
  'smtp_password' => 'your_email_password',
  ```

### ✅ cPanel Setup

- [ ] **Create MySQL Database**
  - Database name: `neoz6813_TB-K1-Database`
  - Create database user with all privileges
  - Import `database.sql` via phpMyAdmin

- [ ] **Upload Files**
  - Upload all files to `/public_html/TB-K1-Database/`
  - Ensure folder structure is preserved
  - Verify `.htaccess` files are uploaded (both root and `/public`)

- [ ] **Set Document Root**
  - In cPanel → Domains → Manage → Document Root
  - Set to: `/public_html/TB-K1-Database/public`
  - OR create subdomain pointing to `/public` folder

- [ ] **File Permissions**
  - Directories: `755`
  - Files: `644`
  - Ensure Apache can write to `/tmp` folder (if using file-based sessions)

- [ ] **Enable mod_rewrite**
  - Verify Apache mod_rewrite is enabled
  - Test `.htaccess` is working

### ✅ SSL Certificate

- [ ] **Install SSL Certificate**
  - Use cPanel AutoSSL (free)
  - OR install Let's Encrypt via cPanel SSL/TLS
  - Force HTTPS redirect in `.htaccess`

- [ ] **Update App URL**
  - Edit `config/app.php`:
    ```php
    'url' => 'https://lapakgaming.neoverse.my.id',
    ```

### ✅ Email Testing

- [ ] **Test Email Sending**
  ```bash
  # Register a new account and verify email arrives
  # Test forgot password email
  # Test order notification email
  ```

- [ ] **SPF/DKIM Records** (Optional but recommended)
  - Add SPF record in cPanel → Zone Editor
  - Add DKIM record for better deliverability

### ✅ PHP Configuration

- [ ] **Check PHP Version**
  - Minimum: PHP 8.0
  - Verify in cPanel → Select PHP Version

- [ ] **Enable Required Extensions**
  - `pdo_mysql` ✅
  - `mbstring` ✅
  - `json` ✅
  - `openssl` ✅
  - `curl` ✅

- [ ] **PHP Settings** (php.ini or .htaccess)
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  max_execution_time = 300
  memory_limit = 256M
  ```

---

## Post-Deployment Testing

### ✅ Frontend Tests

- [ ] **Homepage Loads** - Visit `https://lapakgaming.neoverse.my.id`
- [ ] **Login Page** - Visit `/login` and check form displays
- [ ] **Product Listing** - Verify products load with images
- [ ] **Dark Mode Toggle** - Test theme switching works
- [ ] **Mobile Responsive** - Test on mobile device

### ✅ API Tests

Use the examples in `API_TESTING.md`. Quick test:

```bash
# 1. Test Registration
curl -X POST https://lapakgaming.neoverse.my.id/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","username":"testuser","password":"Test123","full_name":"Test User","phone":"081234567890","role":"buyer"}'

# 2. Test Login
curl -X POST https://lapakgaming.neoverse.my.id/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"Test123"}'

# 3. Test Products (No Auth Required)
curl https://lapakgaming.neoverse.my.id/api/products
```

- [ ] Registration returns 201 with success message
- [ ] Login returns JWT token
- [ ] Products endpoint returns product list
- [ ] Token authentication works on protected endpoints

### ✅ Database Tests

- [ ] **Check Tables Created**
  ```sql
  SHOW TABLES; -- Should show 11 tables
  ```

- [ ] **Verify Foreign Keys**
  ```sql
  SELECT * FROM information_schema.TABLE_CONSTRAINTS 
  WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
  AND TABLE_SCHEMA = 'neoz6813_TB-K1-Database';
  ```

- [ ] **Test Seed Data**
  ```sql
  SELECT COUNT(*) FROM users; -- Should have 3+ users
  SELECT COUNT(*) FROM products; -- Should have 4 products
  SELECT COUNT(*) FROM categories; -- Should have 5 categories
  ```

### ✅ Functional Tests

- [ ] **User Registration Flow**
  - Register new account
  - Receive verification email
  - Click verification link
  - Login successfully

- [ ] **Product Management** (As Seller)
  - Create new product
  - Edit product
  - Deactivate product
  - View product stats

- [ ] **Order Flow** (As Buyer)
  - Add funds to wallet (deposit)
  - Create order for product
  - Receive order confirmation email

- [ ] **Escrow Flow** (Buyer + Seller)
  - Seller delivers order
  - Buyer receives delivery notification
  - Buyer confirms delivery
  - Seller receives payment

- [ ] **Chat System**
  - Send message to seller
  - Receive message notification
  - Check unread count updates (AJAX polling)

- [ ] **Review System**
  - Submit review after completed order
  - Seller responds to review
  - Check product rating updates

---

## Performance Optimization

### ✅ Database Optimization

- [ ] **Enable Query Caching** (if available)
- [ ] **Add Indexes** - Already included in `database.sql`:
  ```sql
  -- Verify indexes exist:
  SHOW INDEX FROM products;
  SHOW INDEX FROM orders;
  ```

- [ ] **Regular Maintenance**
  ```sql
  OPTIMIZE TABLE products;
  OPTIMIZE TABLE orders;
  OPTIMIZE TABLE wallet_transactions;
  ```

### ✅ Frontend Optimization

- [ ] **Enable Gzip Compression** - Add to `.htaccess`:
  ```apache
  <IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript application/javascript application/json
  </IfModule>
  ```

- [ ] **Browser Caching** - Add to `.htaccess`:
  ```apache
  <IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
  </IfModule>
  ```

- [ ] **CDN for TailwindCSS** - Already using CDN (no change needed)

---

## Security Hardening

### ✅ File Security

- [ ] **Disable Directory Listing** - Add to `.htaccess`:
  ```apache
  Options -Indexes
  ```

- [ ] **Protect Config Files**
  ```apache
  <FilesMatch "^(config|database)\.php$">
    Order allow,deny
    Deny from all
  </FilesMatch>
  ```

- [ ] **Hide `.env` if created** (not used in this project, but good practice)

### ✅ SQL Injection Prevention

- [x] **PDO Prepared Statements** - Already used throughout codebase
- [x] **Input Validation** - Already implemented in controllers
- [x] **XSS Protection** - `SecurityHelper::sanitize()` already in use

### ✅ Rate Limiting

- [x] **Login Rate Limiting** - Already implemented (5 attempts = 15min lockout)
- [ ] **API Rate Limiting** - Consider adding IP-based rate limiting:
  ```php
  // Add to middleware if needed
  // Check request count per IP per minute
  ```

### ✅ HTTPS Enforcement

- [ ] **Force HTTPS Redirect** - Add to root `.htaccess`:
  ```apache
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
  ```

---

## Monitoring Setup

### ✅ Error Logging

- [ ] **Enable Error Logging** - Update `config/app.php`:
  ```php
  'debug' => false, // Set to false in production
  'log_errors' => true,
  'error_log' => '../storage/logs/error.log',
  ```

- [ ] **Create Log Directory**
  ```bash
  mkdir -p storage/logs
  chmod 755 storage/logs
  ```

- [ ] **Monitor Error Logs**
  ```bash
  tail -f storage/logs/error.log
  ```

### ✅ Backup Strategy

- [ ] **Database Backup** - Set up cPanel cron job:
  ```bash
  # Daily at 2 AM
  0 2 * * * mysqldump -u neoz6813_TB-K1-Database -p neoz6813_TB-K1-Database > backup_$(date +\%Y\%m\%d).sql
  ```

- [ ] **File Backup** - Use cPanel Backup feature (weekly recommended)

### ✅ Uptime Monitoring

- [ ] **Setup Uptime Monitor** (UptimeRobot, Pingdom, etc.)
  - Monitor: `https://lapakgaming.neoverse.my.id`
  - Check interval: 5 minutes
  - Alert via email if down

---

## Go-Live Checklist

### Final Steps Before Launch

- [ ] All security configurations complete
- [ ] SSL certificate installed and working
- [ ] Email sending tested
- [ ] Test user flows completed
- [ ] Demo data removed (if desired)
- [ ] Error logging enabled
- [ ] Backup system configured
- [ ] Uptime monitoring active

### Post-Launch Tasks

- [ ] Monitor error logs for first 24 hours
- [ ] Test with real users
- [ ] Monitor database performance
- [ ] Check email deliverability
- [ ] Review server resources (CPU, RAM, disk usage)

---

## Common Issues & Solutions

### Issue: 404 Error on All Pages

**Solution:** mod_rewrite not working
```apache
# Add to .htaccess
RewriteEngine On
RewriteBase /TB-K1-Database/public/
```

### Issue: Database Connection Failed

**Solution:** Check credentials in `config/database.php`
```bash
# Test MySQL connection via CLI
mysql -h localhost -u neoz6813_TB-K1-Database -p
```

### Issue: Emails Not Sending

**Solution:** 
1. Check SMTP credentials in `config/mail.php`
2. Test with different SMTP port (587 instead of 465)
3. Verify SPF/DKIM records
4. Check spam folder

### Issue: JWT Token Invalid

**Solution:** Time sync issue or secret mismatch
```php
// Ensure JWT_SECRET is same across all requests
// Check server time: date_default_timezone_set('Asia/Jakarta');
```

### Issue: CORS Errors (if using external frontend)

**Solution:** Add CORS headers to `public/index.php`
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

---

## Support Resources

- **Documentation**: README.md
- **API Reference**: API_TESTING.md
- **Quick Start**: QUICKSTART.md
- **Database Schema**: database.sql

---

**🎉 Ready to launch your marketplace!**

After completing this checklist, your Lapak Gaming marketplace will be production-ready and secure.

# Quick Start Guide - Lapak Gaming Marketplace

## 🚀 5-Minute Setup Guide

### Step 1: Database Setup (2 minutes)

1. **Create MySQL Database in cPanel:**
   ```
   Database Name: neoz6813_TB-K1-Database
   Username: neoz6813
   Password: @Webihsananwar33
   ```

2. **Import Schema:**
   - Open phpMyAdmin
   - Select your database
   - Click "Import"
   - Choose `database.sql`
   - Click "Go"

### Step 2: Configure Files (1 minute)

All configuration files are already set up with your credentials:

- ✅ Database: `config/database.php`
- ✅ Email: `config/mail.php`
- ✅ App: `config/app.php`

**⚠️ IMPORTANT**: Change `jwt_secret` in `config/app.php` for production!

```php
// config/app.php
'jwt_secret' => 'your-unique-secret-key-here-2026',
```

### Step 3: Set Document Root (1 minute)

In cPanel → Domains → Document Root:

```
/home/neoz6813/public_html/TB-K1-Database/public
```

Or if in subdirectory, update `.htaccess` in root.

### Step 4: Test Installation (1 minute)

Visit your domain:
```
https://lapakgaming.neoverse.my.id
```

You should see the homepage!

---

## 🧪 Testing the System

### Test 1: Login as Admin

1. Go to: `/login`
2. Email: `admin@lapakgaming.neoverse.my.id`
3. Password: `password`
4. Click "Sign in"
5. You should be redirected to admin dashboard

### Test 2: API Test (Using curl or Postman)

#### Register New User
```bash
curl -X POST https://lapakgaming.neoverse.my.id/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "testbuyer@example.com",
    "username": "testbuyer",
    "password": "SecurePass123",
    "full_name": "Test Buyer",
    "phone": "081234567890",
    "role": "buyer"
  }'
```

#### Login
```bash
curl -X POST https://lapakgaming.neoverse.my.id/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "buyer@demo.com",
    "password": "password"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "refresh_token": "abc123...",
    "user": {
      "id": 3,
      "email": "buyer@demo.com",
      "username": "demo_buyer",
      "role": "buyer"
    }
  }
}
```

#### Get Products
```bash
curl https://lapakgaming.neoverse.my.id/api/products
```

#### Get User Profile (Authenticated)
```bash
curl https://lapakgaming.neoverse.my.id/api/user/profile \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN_HERE"
```

### Test 3: Complete Buyer Flow

1. **Register/Login as Buyer**
   - Use demo buyer: `buyer@demo.com` / `password`

2. **Check Wallet Balance**
   ```bash
   curl https://lapakgaming.neoverse.my.id/api/wallet \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

3. **Browse Products**
   - Visit: `/products`
   - Click on a product

4. **Purchase Product**
   ```bash
   curl -X POST https://lapakgaming.neoverse.my.id/api/orders \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "product_id": 1,
       "quantity": 1,
       "payment_method": "wallet",
       "notes": "Please deliver ASAP"
     }'
   ```

5. **Check Order Status**
   ```bash
   curl https://lapakgaming.neoverse.my.id/api/orders \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

### Test 4: Complete Seller Flow

1. **Login as Seller**
   - Use demo seller: `seller@demo.com` / `password`

2. **Create Product**
   ```bash
   curl -X POST https://lapakgaming.neoverse.my.id/api/products \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "name": "Test Product - Mobile Legends Account",
       "description": "High tier account with many skins",
       "price": 500000,
       "discount_price": 450000,
       "category_id": 6,
       "product_type": "account",
       "delivery_method": "manual",
       "stock_type": "limited",
       "stock_quantity": 1
     }'
   ```

3. **View Seller Orders**
   ```bash
   curl https://lapakgaming.neoverse.my.id/api/seller/orders \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

4. **Deliver Order**
   ```bash
   curl -X POST https://lapakgaming.neoverse.my.id/api/orders/1/deliver \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "digital_items": {
         "email": "account@example.com",
         "password": "AccountPassword123",
         "note": "Please change password after login"
       }
     }'
   ```

5. **Check Wallet (After Buyer Confirms)**
   ```bash
   curl https://lapakgaming.neoverse.my.id/api/wallet \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

---

## 📱 Frontend Testing

### Homepage Features
- ✅ Browse categories
- ✅ View featured products
- ✅ Search products (real-time AJAX)
- ✅ Dark mode toggle
- ✅ Responsive design

### User Dashboard
- ✅ View wallet balance
- ✅ Recent orders
- ✅ Order tracking
- ✅ Notifications (polling every 3s)
- ✅ Chat system

### Seller Dashboard
- ✅ Product management (CRUD)
- ✅ Order management
- ✅ Sales analytics
- ✅ Wallet/earnings
- ✅ Seller level badge

### Admin Dashboard
- ✅ Platform statistics
- ✅ User management
- ✅ Product moderation
- ✅ Order monitoring
- ✅ Dispute resolution

---

## 🔐 Security Checklist

Before going live:

- [ ] Change JWT secret in `config/app.php`
- [ ] Change all default passwords
- [ ] Enable SSL certificate (Let's Encrypt)
- [ ] Verify email SMTP settings
- [ ] Test email delivery
- [ ] Set restrictive file permissions (644 for files, 755 for directories)
- [ ] Disable directory listing (already done in .htaccess)
- [ ] Review error logging settings
- [ ] Test all payment flows
- [ ] Test escrow system

---

## 🐛 Common Issues & Solutions

### Issue: "Route not found" on all pages

**Cause**: mod_rewrite not enabled or .htaccess not working

**Solution**:
1. Check if mod_rewrite is enabled in Apache
2. Verify .htaccess files exist in root and `/public`
3. Check AllowOverride directive in Apache config

### Issue: "Database connection failed"

**Cause**: Incorrect credentials or database doesn't exist

**Solution**:
1. Verify database name, username, password in `config/database.php`
2. Ensure database exists in cPanel MySQL
3. Grant all privileges to user on database

### Issue: Email not sending

**Cause**: SMTP settings incorrect

**Solution**:
1. Verify SMTP credentials in `config/mail.php`
2. Test SMTP connection from cPanel
3. Check if port 465 is open (SSL)
4. Try port 587 (TLS) if 465 fails

### Issue: JWT decode errors

**Cause**: Token expired or invalid

**Solution**:
1. Clear browser localStorage
2. Login again to get new token
3. Use refresh token endpoint to get new access token

### Issue: 500 Internal Server Error

**Cause**: PHP errors

**Solution**:
1. Enable error reporting in PHP (dev only):
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
2. Check PHP error logs in cPanel
3. Verify PHP version is 8.0+

---

## 📊 Monitoring & Maintenance

### Daily Tasks
- [ ] Check new user registrations
- [ ] Review disputed orders
- [ ] Monitor email deliverability
- [ ] Check error logs

### Weekly Tasks
- [ ] Review platform revenue
- [ ] Verify seller withdrawals
- [ ] Update featured products
- [ ] Analyze sales trends

### Monthly Tasks
- [ ] Backup database
- [ ] Review security logs
- [ ] Update seller levels
- [ ] Generate reports

---

## 🎯 Next Steps

1. **Customize Design**
   - Update logo in `app/views/home.php`
   - Modify color scheme in TailwindCSS
   - Add custom CSS in `public/assets/css/`

2. **Add Payment Gateway**
   - Integrate Midtrans/Xendit
   - Update deposit/withdrawal logic
   - Add payment proof upload

3. **Enhance Features**
   - Add product image upload
   - Implement PDF invoice
   - Add advanced analytics
   - Enable two-factor authentication

4. **Marketing**
   - SEO optimization
   - Social media integration
   - Affiliate system
   - Email marketing

---

## 📞 Support

For technical issues:
- Email: administrator@lapakgaming.neoverse.my.id
- Check README.md for detailed documentation
- Review inline code comments

---

**Congratulations! Your marketplace is ready to launch! 🚀**

Remember to change default passwords and JWT secret before going live!

# API Testing Guide - Lapak Gaming Marketplace

## Complete API Reference with Examples

Base URL: `https://lapakgaming.neoverse.my.id/api`

---

## 🔐 Authentication Endpoints

### 1. Register New User

**POST** `/auth/register`

```json
// Request
{
  "email": "newuser@example.com",
  "username": "newuser123",
  "password": "SecurePassword123",
  "full_name": "John Doe",
  "phone": "081234567890",
  "role": "buyer"  // or "seller"
}

// Success Response (201)
{
  "success": true,
  "message": "Registration successful! Please check your email to verify your account.",
  "data": {
    "user_id": 4
  }
}

// Error Response (400)
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": "Email already registered"
  }
}
```

### 2. Login

**POST** `/auth/login`

```json
// Request
{
  "email": "buyer@demo.com",
  "password": "password"
}

// Success Response (200)
{
  "success": true,
  "message": "Login successful",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "refresh_token": "a1b2c3d4e5f6g7h8i9j0...",
    "user": {
      "id": 3,
      "email": "buyer@demo.com",
      "username": "demo_buyer",
      "full_name": "Demo Buyer",
      "role": "buyer",
      "seller_level": "bronze",
      "avatar": null
    }
  }
}

// Error Response (401)
{
  "success": false,
  "message": "Invalid credentials"
}
```

### 3. Verify Email

**POST** `/auth/verify-email?token=VERIFICATION_TOKEN`

```json
// Success Response (200)
{
  "success": true,
  "message": "Email verified successfully! You can now login."
}
```

### 4. Forgot Password

**POST** `/auth/forgot-password`

```json
// Request
{
  "email": "user@example.com"
}

// Response (200) - Always success to prevent email enumeration
{
  "success": true,
  "message": "If the email exists, you will receive a password reset link"
}
```

### 5. Reset Password

**POST** `/auth/reset-password`

```json
// Request
{
  "token": "reset_token_from_email",
  "password": "NewSecurePassword123"
}

// Success Response (200)
{
  "success": true,
  "message": "Password reset successful! You can now login with your new password."
}
```

### 6. Refresh Token

**POST** `/auth/refresh-token`

```json
// Request
{
  "refresh_token": "a1b2c3d4e5f6g7h8i9j0..."
}

// Success Response (200)
{
  "success": true,
  "message": "Token refreshed",
  "data": {
    "access_token": "new_jwt_token_here..."
  }
}
```

### 7. Logout

**POST** `/auth/logout`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "refresh_token": "a1b2c3d4e5f6g7h8i9j0..."
}

// Success Response (200)
{
  "success": true,
  "message": "Logout successful"
}
```

---

## 👤 User Profile Endpoints

### 1. Get Profile

**GET** `/user/profile`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "User profile",
  "data": {
    "user": {
      "id": 3,
      "email": "buyer@demo.com",
      "username": "demo_buyer",
      "role": "buyer",
      "full_name": "Demo Buyer",
      "phone": "089876543210",
      "avatar": null,
      "email_verified_at": "2026-03-04 10:30:00",
      "is_active": true,
      "seller_level": "bronze",
      "total_sales": 0,
      "created_at": "2026-03-04 10:30:00"
    },
    "wallet": {
      "id": 3,
      "user_id": 3,
      "balance": "1000000.00",
      "pending_balance": "0.00",
      "total_earned": "0.00",
      "total_spent": "0.00"
    }
  }
}
```

### 2. Update Profile

**PUT** `/user/profile`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "full_name": "John Updated Doe",
  "phone": "081234567890",
  "avatar": "https://example.com/avatar.jpg"
}

// Success Response (200)
{
  "success": true,
  "message": "Profile updated successfully"
}
```

### 3. Change Password

**PUT** `/user/change-password`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "current_password": "OldPassword123",
  "new_password": "NewPassword456"
}

// Success Response (200)
{
  "success": true,
  "message": "Password changed successfully"
}
```

---

## 🛍 Product Endpoints

### 1. Get All Products

**GET** `/products?page=1&limit=20`

```json
// Success Response (200)
{
  "success": true,
  "message": "Products retrieved",
  "data": {
    "products": [
      {
        "id": 1,
        "seller_id": 2,
        "category_id": 6,
        "name": "Mobile Legends Account - Mythic Glory 800 Points",
        "slug": "ml-mythic-glory-800",
        "description": "Premium account...",
        "price": "1500000.00",
        "discount_price": "1350000.00",
        "product_type": "account",
        "stock_quantity": 1,
        "thumbnail": "ml-account-1.jpg",
        "sold_count": 45,
        "rating_avg": "4.80",
        "rating_count": 12,
        "is_active": true,
        "is_featured": true,
        "seller_username": "demo_seller",
        "seller_level": "gold",
        "category_name": "Mobile Legends"
      }
    ],
    "page": 1,
    "limit": 20
  }
}
```

### 2. Get Product Detail

**GET** `/products/:id`

```json
// Success Response (200)
{
  "success": true,
  "message": "Product detail",
  "data": {
    "product": {
      "id": 1,
      "name": "Mobile Legends Account - Mythic Glory 800 Points",
      "description": "Full description here...",
      "price": "1500000.00",
      "discount_price": "1350000.00",
      "seller_username": "demo_seller",
      "seller_level": "gold",
      "seller_total_sales": 78,
      "category_name": "Mobile Legends",
      "view_count": 1234,
      "sold_count": 45,
      "rating_avg": "4.80"
    }
  }
}
```

### 3. Search Products

**GET** `/products/search?q=mobile+legends&limit=20`

```json
// Success Response (200)
{
  "success": true,
  "message": "Search results",
  "data": {
    "products": [...],
    "keyword": "mobile legends"
  }
}
```

### 4. Create Product (Seller Only)

**POST** `/products`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "name": "Free Fire Account - Level 80",
  "description": "High level account with rare bundles",
  "price": 800000,
  "discount_price": 750000,
  "category_id": 7,
  "product_type": "account",
  "delivery_method": "manual",
  "stock_type": "limited",
  "stock_quantity": 1,
  "thumbnail": "ff-account.jpg"
}

// Success Response (201)
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "product_id": 5
  }
}
```

### 5. Update Product (Seller Only)

**PUT** `/products/:id`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "price": 850000,
  "stock_quantity": 2,
  "is_active": true
}

// Success Response (200)
{
  "success": true,
  "message": "Product updated successfully"
}
```

---

## 🛒 Order Endpoints (Escrow System)

### 1. Create Order

**POST** `/orders`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "product_id": 1,
  "quantity": 1,
  "payment_method": "wallet",
  "notes": "Please deliver to email ABC@example.com"
}

// Success Response (201)
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "order_id": 5,
    "order_number": "ORD-20260304-AB12CD34",
    "total_amount": "1417500.00"  // Including 5% platform fee
  }
}
```

### 2. Get My Orders (Buyer)

**GET** `/orders`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Your orders",
  "data": {
    "orders": [
      {
        "id": 5,
        "order_number": "ORD-20260304-AB12CD34",
        "seller_username": "demo_seller",
        "total_amount": "1417500.00",
        "status": "processing",
        "created_at": "2026-03-04 11:00:00",
        "items": [...]
      }
    ]
  }
}
```

### 3. Upload Payment Proof

**POST** `/orders/:id/upload-payment`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "payment_proof": "payment_20260304.jpg"
}

// Success Response (200)
{
  "success": true,
  "message": "Payment proof uploaded successfully"
}
```

### 4. Deliver Order (Seller)

**POST** `/orders/:id/deliver`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "digital_items": {
    "email": "mlaccount@example.com",
    "password": "AccountPass123",
    "note": "Please change password after first login",
    "additional_info": "Server: Asia, Region: Indonesia"
  }
}

// Success Response (200)
{
  "success": true,
  "message": "Order delivered successfully"
}
```

### 5. Confirm Delivery (Buyer - Releases Escrow)

**POST** `/orders/:id/confirm`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Order completed successfully. Payment released to seller."
}

// Note: This action:
// 1. Transfers funds from seller's pending_balance to balance
// 2. Records transaction in wallet_transactions
// 3. Updates order status to "completed"
// 4. Platform fee is deducted
// 5. Seller total_sales incremented
// 6. Seller level recalculated
```

### 6. Raise Dispute

**POST** `/orders/:id/dispute`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "reason": "Account credentials not working. Tried multiple times."
}

// Success Response (200)
{
  "success": true,
  "message": "Dispute submitted successfully. Admin will review your case."
}
```

---

## 💰 Wallet Endpoints

### 1. Get Balance

**GET** `/wallet`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Wallet balance",
  "data": {
    "wallet": {
      "id": 3,
      "user_id": 3,
      "balance": "500000.00",
      "pending_balance": "0.00",
      "total_earned": "1500000.00",
      "total_spent": "1000000.00"
    }
  }
}
```

### 2. Get Transactions

**GET** `/wallet/transactions?limit=50`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Wallet transactions",
  "data": {
    "transactions": [
      {
        "id": 10,
        "wallet_id": 3,
        "type": "payment",
        "amount": "1417500.00",
        "balance_before": "1500000.00",
        "balance_after": "82500.00",
        "description": "Payment for order ORD-20260304-AB12CD34",
        "status": "completed",
        "created_at": "2026-03-04 11:00:00"
      }
    ]
  }
}
```

### 3. Deposit

**POST** `/wallet/deposit`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "amount": 100000,
  "payment_method": "bank_transfer"
}

// Success Response (200)
{
  "success": true,
  "message": "Deposit successful",
  "data": {
    "new_balance": "600000.00"
  }
}
```

### 4. Withdraw

**POST** `/wallet/withdraw`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "amount": 500000,
  "bank_name": "BCA",
  "account_number": "1234567890",
  "account_name": "John Doe"
}

// Success Response (200)
{
  "success": true,
  "message": "Withdrawal request submitted",
  "data": {
    "new_balance": "0.00"
  }
}
```

---

## 💬 Chat Endpoints

### 1. Get Conversations

**GET** `/chat/conversations`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Conversations",
  "data": {
    "conversations": [
      {
        "contact_id": 2,
        "contact_username": "demo_seller",
        "contact_avatar": null,
        "last_message": "Your order has been delivered",
        "last_message_time": "2026-03-04 11:30:00",
        "unread_count": 1
      }
    ]
  }
}
```

### 2. Get Messages

**GET** `/chat/:userId?limit=50`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Messages",
  "data": {
    "messages": [
      {
        "id": 1,
        "sender_id": 2,
        "receiver_id": 3,
        "message": "Hello! Interested in this account?",
        "is_read": true,
        "created_at": "2026-03-04 10:00:00"
      }
    ]
  }
}
```

### 3. Send Message

**POST** `/chat/:userId`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "message": "Yes, please provide more details",
  "order_id": 5  // Optional
}

// Success Response (201)
{
  "success": true,
  "message": "Message sent",
  "data": {
    "message_id": 2
  }
}
```

---

## ⭐ Review Endpoints

### 1. Create Review

**POST** `/reviews`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Request
{
  "order_id": 5,
  "rating": 5,
  "comment": "Excellent seller! Fast delivery and account works perfectly!"
}

// Success Response (201)
{
  "success": true,
  "message": "Review submitted successfully",
  "data": {
    "review_id": 1
  }
}
```

### 2. Get Product Reviews

**GET** `/reviews/product/:productId?limit=20`

```json
// Success Response (200)
{
  "success": true,
  "message": "Product reviews",
  "data": {
    "reviews": [
      {
        "id": 1,
        "rating": 5,
        "comment": "Excellent seller!",
        "buyer_username": "demo_buyer",
        "buyer_avatar": null,
        "seller_response": "Thank you for your purchase!",
        "created_at": "2026-03-04 12:00:00"
      }
    ]
  }
}
```

---

## 🔔 Notification Endpoints

### 1. Get Notifications

**GET** `/notifications?limit=20`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Notifications",
  "data": {
    "notifications": [
      {
        "id": 1,
        "type": "order_delivered",
        "title": "Order Delivered",
        "message": "Your order #ORD-20260304 has been delivered",
        "link": "/orders/5",
        "is_read": false,
        "created_at": "2026-03-04 11:30:00"
      }
    ]
  }
}
```

### 2. Get Unread Count (For Polling)

**GET** `/notifications/unread-count`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Unread count",
  "data": {
    "unread_count": 3
  }
}
```

---

## 📊 Dashboard Endpoints

### Buyer Dashboard

**GET** `/dashboard/buyer`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Buyer dashboard",
  "data": {
    "wallet": {...},
    "stats": {
      "total_orders": 10,
      "completed_orders": 8,
      "pending_orders": 2,
      "total_spent": "5000000.00"
    },
    "recent_orders": [...]
  }
}
```

### Seller Dashboard

**GET** `/dashboard/seller`
**Headers:** `Authorization: Bearer {access_token}`

```json
// Success Response (200)
{
  "success": true,
  "message": "Seller dashboard",
  "data": {
    "seller": {
      "seller_level": "gold",
      "total_sales": 150
    },
    "wallet": {...},
    "stats": {
      "total_products": 20,
      "active_products": 18,
      "total_orders": 150,
      "completed_orders": 145,
      "total_revenue": "75000000.00",
      "pending_balance": "500000.00",
      "available_balance": "20000000.00"
    }
  }
}
```

---

## 📌 Important Notes

1. **Authentication**: All protected endpoints require `Authorization: Bearer {access_token}` header
2. **Token Expiry**: Access tokens expire in 1 hour. Use refresh token endpoint to get new one.
3. **AJAX Polling**: Frontend polls `/notifications/unread-count` every 3 seconds
4. **Escrow Flow**: 
   - Order created → Funds deducted from buyer
   - Seller delivers → Buyer confirms → Funds released to seller
   - Platform fee automatically deducted on completion
5. **Error Codes**:
   - 200: Success
   - 201: Created
   - 400: Bad Request
   - 401: Unauthorized
   - 403: Forbidden
   - 404: Not Found
   - 422: Validation Error

---

**For complete documentation, see README.md**

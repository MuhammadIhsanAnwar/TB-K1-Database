# API Testing Guide

Testing endpoints menggunakan Postman atau cURL.

Base URL: `http://localhost:5000/api`

## 1. Authentication

### Register
```bash
POST /auth/register
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "role": "buyer"
}
```

### Login
```bash
POST /auth/login
Content-Type: application/json

{
  "email": "admin@lapakgaming.neoverse.my.id",
  "password": "admin123"
}

# Response akan include accessToken
# Simpan token untuk request berikutnya
```

### Verify Email
```bash
POST /auth/verify-email
Content-Type: application/json

{
  "token": "[verification_token_from_email]"
}
```

## 2. Products

### Get All Products
```bash
GET /products?category=1&search=game&page=1&limit=10
```

### Get Product Detail
```bash
GET /products/1
```

### Create Product (Seller only)
```bash
POST /products
Authorization: Bearer [your_access_token]
Content-Type: application/json

{
  "title": "Diamonds Mobile Legends",
  "description": "Top-up diamond ML murah",
  "price": 50000,
  "category_id": 1,
  "stock": 100,
  "auto_delivery": true,
  "delivery_content": "Code: XXXX-XXXX",
  "images": ["https://example.com/image.jpg"]
}
```

## 3. Orders

### Create Order
```bash
POST /orders
Authorization: Bearer [your_access_token]
Content-Type: application/json

{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ],
  "payment_method": "transfer",
  "notes": "Please process quickly"
}
```

### Get My Orders (Buyer)
```bash
GET /orders/buyer/my-orders?status=pending&page=1
Authorization: Bearer [your_access_token]
```

### Upload Payment Proof
```bash
PUT /orders/1/payment-proof
Authorization: Bearer [your_access_token]
Content-Type: application/json

{
  "payment_proof": "https://example.com/bukti-transfer.jpg"
}
```

### Confirm Order (Buyer)
```bash
PUT /orders/1/confirm
Authorization: Bearer [your_access_token]
```

## 4. Wallet

### Get Wallet Balance
```bash
GET /wallet
Authorization: Bearer [your_access_token]
```

### Request Deposit
```bash
POST /wallet/deposit
Authorization: Bearer [your_access_token]
Content-Type: application/json

{
  "amount": 100000,
  "payment_proof": "Proof description"
}
```

### Request Withdrawal
```bash
POST /wallet/withdraw
Authorization: Bearer [your_access_token]
Content-Type: application/json

{
  "amount": 50000,
  "bank_name": "BCA",
  "account_number": "1234567890",
  "account_name": "John Doe"
}
```

## 5. Reviews

### Create Review
```bash
POST /reviews
Authorization: Bearer [your_access_token]
Content-Type: application/json

{
  "order_id": 1,
  "rating": 5,
  "comment": "Excellent product and fast delivery!"
}
```

## 6. Admin Endpoints

### Get Dashboard Analytics
```bash
GET /admin/dashboard
Authorization: Bearer [admin_access_token]
```

### Suspend User
```bash
PUT /admin/users/5/suspend
Authorization: Bearer [admin_access_token]
Content-Type: application/json

{
  "suspend": true,
  "reason": "Violation of terms"
}
```

### Moderate Product
```bash
PUT /admin/products/10/moderate
Authorization: Bearer [admin_access_token]
Content-Type: application/json

{
  "status": "active",
  "reason": "Approved"
}
```

### Approve Deposit
```bash
PUT /wallet/admin/approve/5
Authorization: Bearer [admin_access_token]
```

## Headers untuk Authenticated Requests

```
Authorization: Bearer [your_access_token]
Content-Type: application/json
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": [ ... ]
}
```

## Testing dengan Postman

1. Import collection
2. Set environment variable `API_URL` = `http://localhost:5000/api`
3. Login untuk mendapat token
4. Set token di authorization header
5. Test endpoints

## Testing dengan cURL

```bash
# Login
curl -X POST http://localhost:5000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@lapakgaming.neoverse.my.id","password":"admin123"}'

# Get products
curl -X GET http://localhost:5000/api/products

# Create order (dengan token)
curl -X POST http://localhost:5000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"items":[{"product_id":1,"quantity":1}],"payment_method":"transfer"}'
```

---

Untuk testing WebSocket (Chat), gunakan Socket.IO client atau browser console.

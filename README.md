# 🎮 Lapak Gaming - Digital Marketplace Platform

A complete, production-ready digital marketplace platform for game items, vouchers, accounts, and top-up services. Built with modern web technologies for scalability and performance.

## 🚀 Tech Stack

### Backend
- **Runtime**: Node.js
- **Framework**: Express.js
- **Database**: MySQL
- **Authentication**: JWT + Refresh Tokens
- **Email**: Nodemailer (with provided SMTP credentials)
- **Real-time**: Socket.IO for chat
- **Security**: Helmet, bcrypt, rate limiting, CORS

### Frontend
- **Framework**: Next.js 14 (React 18)
- **Styling**: TailwindCSS
- **State Management**: Redux Toolkit
- **HTTP Client**: Axios
- **UI Components**: React Icons, React Toastify
- **Real-time**: Socket.IO Client

## 📋 Features

### User Roles
1. **Guest** - Browse products, search, view details
2. **Buyer** - Purchase products, manage orders, chat, reviews, wallet
3. **Seller** - List products, manage inventory, process orders, analytics
4. **Admin** - Full platform management, moderation, analytics

### Core Features
- ✅ JWT Authentication with refresh tokens
- ✅ Email verification & password reset
- ✅ Product catalog with categories
- ✅ Advanced search & filtering
- ✅ Shopping cart system
- ✅ Escrow payment system
- ✅ Internal wallet system
- ✅ Real-time chat (WebSocket)
- ✅ Rating & review system
- ✅ Order management
- ✅ Admin dashboard with analytics
- ✅ Dark/Light mode
- ✅ Responsive design

## 🗄️ Database Schema

The platform uses the following main tables:
- `users` - User accounts with roles
- `products` - Product listings
- `categories` - Product categories
- `orders` - Order transactions
- `order_items` - Order line items
- `wallets` - User wallet balances
- `wallet_transactions` - Transaction history
- `messages` - Chat messages
- `reviews` - Product reviews
- `notifications` - User notifications
- `disputes` - Order disputes

## 📁 Project Structure

```
TB-K1-Database/
├── backend/
│   ├── config/
│   │   └── database.js
│   ├── controllers/
│   │   ├── authController.js
│   │   ├── productController.js
│   │   ├── orderController.js
│   │   ├── walletController.js
│   │   ├── reviewController.js
│   │   └── adminController.js
│   ├── middleware/
│   │   ├── auth.js
│   │   ├── validate.js
│   │   ├── errorHandler.js
│   │   └── rateLimiter.js
│   ├── routes/
│   │   ├── authRoutes.js
│   │   ├── productRoutes.js
│   │   ├── orderRoutes.js
│   │   ├── walletRoutes.js
│   │   ├── reviewRoutes.js
│   │   └── adminRoutes.js
│   ├── socket/
│   │   └── chatSocket.js
│   ├── utils/
│   │   └── email.js
│   ├── database/
│   │   ├── schema.sql
│   │   └── migrate.js
│   ├── .env
│   ├── package.json
│   └── server.js
├── frontend/
│   ├── components/
│   │   └── Layout.js
│   ├── lib/
│   │   ├── api.js
│   │   └── services.js
│   ├── pages/
│   │   ├── _app.js
│   │   ├── _document.js
│   │   ├── index.js
│   │   ├── login.js
│   │   └── register.js
│   ├── store/
│   │   ├── store.js
│   │   └── slices/
│   │       ├── authSlice.js
│   │       ├── cartSlice.js
│   │       └── themeSlice.js
│   ├── styles/
│   │   └── globals.css
│   ├── .env.local
│   ├── next.config.js
│   ├── tailwind.config.js
│   └── package.json
└── README.md
```

## 🛠️ Installation & Setup

### Prerequisites
- Node.js (v16 or higher)
- MySQL (v8.0 or higher)
- npm or yarn

### Backend Setup

1. Navigate to backend directory:
```bash
cd backend
```

2. Install dependencies:
```bash
npm install
```

3. Configure environment variables (`.env` already configured):
- Database credentials provided
- Email SMTP credentials configured
- JWT secrets set

4. Run database migration:
```bash
npm run migrate
```

5. Start development server:
```bash
npm run dev
```

The backend will run on `http://localhost:5000`

### Frontend Setup

1. Navigate to frontend directory:
```bash
cd frontend
```

2. Install dependencies:
```bash
npm install
```

3. Environment variables (`.env.local` already configured):
- API URL set to backend
- WebSocket URL configured

4. Start development server:
```bash
npm run dev
```

The frontend will run on `http://localhost:3000`

## 🔐 Default Credentials

### Admin Account
- **Email**: admin@lapakgaming.neoverse.my.id
- **Password**: admin123

### Database Connection
- **Host**: localhost (or provided host)
- **Username**: neoz6813
- **Password**: @Webihsananwar33
- **Database**: neoz6813_TB-K1-Database

### Email Configuration
- **Host**: lapakgaming.neoverse.my.id
- **Port**: 465
- **Username**: administrator@lapakgaming.neoverse.my.id
- **Password**: tbsbdk1database

## 📡 API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/verify-email` - Verify email
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `POST /api/auth/refresh` - Refresh access token
- `POST /api/auth/forgot-password` - Request password reset
- `POST /api/auth/reset-password` - Reset password
- `GET /api/auth/me` - Get current user
- `PUT /api/auth/profile` - Update profile
- `DELETE /api/auth/account` - Delete account

### Products
- `GET /api/products` - Get all products (with filters)
- `GET /api/products/:id` - Get product by ID
- `POST /api/products` - Create product (Seller)
- `PUT /api/products/:id` - Update product (Seller)
- `DELETE /api/products/:id` - Delete product (Seller)
- `GET /api/products/categories` - Get categories

### Orders
- `POST /api/orders` - Create order
- `GET /api/orders/buyer/my-orders` - Get buyer orders
- `GET /api/orders/seller/my-orders` - Get seller orders
- `GET /api/orders/:id` - Get order by ID
- `PUT /api/orders/:id/payment-proof` - Upload payment proof
- `PUT /api/orders/:id/process` - Process order (Seller)
- `PUT /api/orders/:id/confirm` - Confirm order (Buyer)
- `PUT /api/orders/:id/cancel` - Cancel order

### Wallet
- `GET /api/wallet` - Get wallet
- `GET /api/wallet/transactions` - Get transactions
- `POST /api/wallet/deposit` - Request deposit
- `POST /api/wallet/withdraw` - Request withdrawal

### Reviews
- `POST /api/reviews` - Create review
- `GET /api/reviews/product/:productId` - Get product reviews

### Admin
- `GET /api/admin/dashboard` - Dashboard analytics
- `GET /api/admin/users` - Get all users
- `PUT /api/admin/users/:id/suspend` - Suspend/unsuspend user
- `PUT /api/admin/users/:id/verify-seller` - Verify seller
- `GET /api/admin/products` - Get all products
- `PUT /api/admin/products/:id/moderate` - Moderate product
- `GET /api/admin/transactions` - Get all transactions
- `GET /api/admin/disputes` - Get disputes
- `PUT /api/admin/disputes/:id/resolve` - Resolve dispute

## 💬 WebSocket Events

### Chat Events
- `authenticate` - Authenticate user
- `send_message` - Send message
- `get_messages` - Get conversation history
- `get_conversations` - Get conversations list
- `mark_read` - Mark messages as read
- `typing` - Send typing indicator
- `stop_typing` - Stop typing indicator

## 🎨 Frontend Pages

### Public Pages
- `/` - Homepage
- `/products` - Product listing
- `/products/[id]` - Product detail
- `/login` - Login page
- `/register` - Registration page
- `/forgot-password` - Password reset request
- `/reset-password` - Reset password

### Buyer Pages
- `/dashboard` - Buyer dashboard
- `/orders` - Order history
- `/wallet` - Wallet management
- `/messages` - Chat inbox
- `/profile` - Profile settings

### Seller Pages
- `/seller/dashboard` - Seller dashboard
- `/seller/products` - Product management
- `/seller/orders` - Order management
- `/seller/analytics` - Sales analytics
- `/seller/withdraw` - Withdraw funds

### Admin Pages
- `/admin/dashboard` - Admin dashboard
- `/admin/users` - User management
- `/admin/products` - Product moderation
- `/admin/transactions` - Transaction monitoring
- `/admin/disputes` - Dispute resolution

## 🔒 Security Features

- Password hashing with bcrypt
- JWT authentication with refresh tokens
- HTTP-only cookies for refresh tokens
- Rate limiting on sensitive endpoints
- Input validation and sanitization
- SQL injection prevention
- CSRF protection
- Helmet security headers
- Role-based access control

## 🌟 Advanced Features

### Escrow System
1. Buyer places order → Payment held in escrow
2. Seller delivers item
3. Buyer confirms receipt
4. Funds released to seller (minus platform fee)

### Wallet System
- Deposit funds
- Withdraw funds
- Transaction history
- Balance tracking
- Admin approval for deposits/withdrawals

### Real-time Chat
- Direct messaging between buyers and sellers
- Message history
- Read receipts
- Typing indicators
- Online status

### Rating System
- 1-5 star ratings
- Review comments
- Anti-spam validation
- Average rating calculation

## 📊 Platform Fee

Default platform fee: 5% of transaction value
Configurable via environment variable `PLATFORM_FEE`

## 🐛 Troubleshooting

### Database Connection Issues
- Verify MySQL is running
- Check credentials in `.env`
- Ensure database exists
- Run migration script

### Email Not Sending
- Verify SMTP credentials
- Check firewall settings
- Test email configuration

### WebSocket Connection Failed
- Check CORS settings
- Verify WebSocket URL
- Ensure ports are open

## 📝 Development Notes

### Running Both Servers
```bash
# Terminal 1 - Backend
cd backend
npm run dev

# Terminal 2 - Frontend
cd frontend
npm run dev
```

### Building for Production
```bash
# Backend
cd backend
npm start

# Frontend
cd frontend
npm run build
npm start
```

## 🤝 Contributing

This is a comprehensive marketplace platform. For modifications:
1. Backend changes: Edit controllers/routes in `backend/`
2. Frontend changes: Edit pages/components in `frontend/`
3. Database changes: Update schema in `database/schema.sql`

## 📄 License

This project is part of a database course assignment.

## 👨‍💻 Developer

Created as part of TB-K1-Database project for Sistem Basis Data course.

---

**Note**: All credentials are pre-configured. Simply run `npm install` and `npm run dev` in both backend and frontend directories to start the application.

# Filament & Jetstream Integration Guide

## Overview
This document outlines the integration of Filament Admin Panel and Jetstream (with teams support) into the Lapak Gaming marketplace application.

## What Was Installed

### 1. **Filament Admin Panel** (v5.6)
- Modern, feature-rich admin panel builder for Laravel
- Includes resource management for models
- Built-in authentication and authorization
- Responsive design with dark/light mode support

### 2. **Jetstream** (v5.5)
- Authentication scaffolding for Laravel
- Built-in support for teams and roles
- Session management
- Security features like two-factor authentication

### 3. **Laravel Fortify** (v1.37)
- Backend authentication for APIs
- Password reset, email verification
- Two-factor authentication

## Integration Implementation

### Configuration Files Created

1. **`config/filament.php`**
   - Admin panel configuration
   - Customized colors and branding
   - User model and authentication setup

2. **`config/jetstream.php`**
   - Stack: Livewire (for interactive UI)
   - Features: API tokens, Teams with invitations
   - Profile photo disk: public storage

### User Model Updates (`app/Models/User.php`)

Added Jetstream traits:
- `HasTeams` - Support for team functionality
- `HasProfilePhoto` - Profile photo management
- Updated `$fillable` array with Jetstream fields
- Added `$appends` for profile photo URL

### Filament Admin Panel Structure

```
app/Filament/Admin/
├── AdminPanelProvider.php      # Main panel configuration
├── Pages/
│   └── Dashboard.php           # Admin dashboard with widgets
├── Widgets/
│   └── StatsOverviewWidget.php # Statistics display
└── Resources/
    ├── UserResource.php        # Users management
    ├── ProductResource.php     # Products management
    ├── OrderResource.php       # Orders management
    ├── CategoryResource.php    # Categories management
    └── [Resource]/Pages/       # CRUD pages for each resource
```

### Resources Available

#### 1. **UserResource** (`/admin/users`)
- View all users with filters
- Create/Edit user information
- Manage roles (Admin, Seller, Buyer)
- Manage seller status (Pending, Approved, Rejected)
- Email verification and two-factor authentication toggles

#### 2. **ProductResource** (`/admin/products`)
- Manage product catalog
- Filter by category and active status
- View pricing and stock information
- Toggle featured status
- Bulk delete operations

#### 3. **OrderResource** (`/admin/orders`)
- Monitor all orders
- Filter by status
- Track total price
- View order details and update status

#### 4. **CategoryResource** (`/admin/categories`)
- Manage product categories
- Support for hierarchical categories (parent/child)
- Manage category images
- Sort categories with custom order

### Dashboard

The admin dashboard includes:
- Total users count
- Active sellers count
- Total products count
- Total orders count
- Total revenue tracking

## How to Access

### Filament Admin Panel
- **URL**: `https://yourapp.com/admin`
- **Requirement**: User must be an admin (role = 'admin')
- **Login**: Uses existing authentication system

### Authentication

The admin panel uses:
1. Existing user authentication
2. Role-based authorization (requires admin role)
3. Middleware: `IsAdmin` at `app/Http/Middleware/IsAdmin.php`

## Database Migrations

To use Jetstream features (teams), run:
```bash
php artisan migrate
```

This will create:
- `teams` table
- `team_user` table
- `team_invitations` table

## Usage Examples

### Access Admin Panel
```php
// In your routes or manually:
// Navigate to: /admin
```

### Check if user is admin
```php
if (auth()->user()->isAdmin()) {
    // User has access to admin panel
}
```

### Create a new admin user
```php
User::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'status' => 'active',
]);
```

## Features by Package

### Filament Features
✅ Resource management (CRUD operations)
✅ Advanced filtering and search
✅ Bulk actions
✅ Customizable forms
✅ Data tables with sorting
✅ Dashboard with widgets
✅ Role-based access control

### Jetstream Features
✅ User authentication
✅ Team management
✅ Profile photo uploads
✅ API token management
✅ Session management
✅ Two-factor authentication

### Fortify Features
✅ Password reset
✅ Email verification
✅ Two-factor authentication
✅ Profile management
✅ Account deletion

## Next Steps

1. **Run migrations** to create Jetstream tables:
   ```bash
   php artisan migrate
   ```

2. **Create an admin user**:
   ```bash
   php artisan tinker
   > User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin', 'status' => 'active'])
   ```

3. **Access the admin panel**: Visit `/admin` and login

4. **Customize resources** by editing files in `app/Filament/Admin/Resources/`

5. **Add new resources** using Filament commands:
   ```bash
   php artisan make:filament-resource ResourceName
   ```

## Customization

### Modifying Admin Panel Colors
Edit `config/filament.php`:
```php
'colors' => [
    'primary' => '#2563eb',    // Change primary color
    'danger' => '#ef4444',
    // Add more colors as needed
]
```

### Adding New Resources
1. Create a new Resource class in `app/Filament/Admin/Resources/`
2. Define forms and tables
3. Create Pages in `[Resource]/Pages/`
4. Filament will automatically register it

### Custom Widgets
Create new widgets in `app/Filament/Admin/Widgets/` for custom dashboards

## Security Considerations

1. **Admin Middleware**: Only users with role 'admin' can access the panel
2. **Email Verification**: Required by default
3. **Two-Factor Authentication**: Available for all users
4. **API Tokens**: Managed through Jetstream
5. **Session Security**: CSRF protection included

## Support

For more information:
- [Filament Documentation](https://filamentphp.com/docs)
- [Jetstream Documentation](https://jetstream.laravel.com)
- [Laravel Documentation](https://laravel.com/docs)

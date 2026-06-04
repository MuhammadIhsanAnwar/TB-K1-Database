#!/bin/bash

# Filament & Jetstream Setup Script
# Run this script to complete the integration setup

echo "🚀 Starting Filament & Jetstream Setup..."
echo ""

# Step 1: Publish Filament assets
echo "📦 Publishing Filament assets..."
php artisan filament:install

# Step 2: Publish Jetstream views and configuration
echo "📦 Publishing Jetstream assets..."
php artisan jetstream:install

# Step 3: Run migrations
echo "🗄️ Running migrations..."
php artisan migrate

# Step 4: Build assets
echo "🎨 Building assets..."
npm install
npm run build

# Step 5: Create an admin user (optional)
echo ""
echo "✅ Setup complete!"
echo ""
echo "To create an admin user, run:"
echo "php artisan tinker"
echo ""
echo "Then in Tinker:"
echo "User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin', 'status' => 'active'])"
echo ""
echo "Access admin panel at: /admin"

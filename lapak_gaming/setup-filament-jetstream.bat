@echo off
REM Filament & Jetstream Setup Script for Windows
REM Run this script to complete the integration setup

echo.
echo 🚀 Starting Filament ^& Jetstream Setup...
echo.

REM Step 1: Publish Filament assets
echo 📦 Publishing Filament assets...
php artisan filament:install

REM Step 2: Publish Jetstream views and configuration
echo 📦 Publishing Jetstream assets...
php artisan jetstream:install

REM Step 3: Run migrations
echo 🗄️  Running migrations...
php artisan migrate

REM Step 4: Build assets
echo 🎨 Building assets...
npm install
npm run build

REM Step 5: Display completion message
echo.
echo ✅ Setup complete!
echo.
echo To create an admin user, run:
echo php artisan tinker
echo.
echo Then in Tinker:
echo User::create(['name' =^> 'Admin', 'email' =^> 'admin@example.com', 'password' =^> bcrypt('password'), 'role' =^> 'admin', 'status' =^> 'active'])
echo.
echo Access admin panel at: /admin
echo.
pause

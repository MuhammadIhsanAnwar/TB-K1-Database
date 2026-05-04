# Script Build Assets untuk Production
# Pastikan sudah di folder project root

Write-Host "==============================================="
Write-Host "🚀 Build Lapak Gaming untuk Production"
Write-Host "==============================================="
Write-Host ""

# Check Node.js
if (-not (Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "❌ npm tidak ditemukan. Install Node.js terlebih dahulu dari https://nodejs.org" -ForegroundColor Red
    exit 1
}

Write-Host "✅ npm ditemukan: $(npm --version)" -ForegroundColor Green

# Check Composer
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer tidak ditemukan. Install Composer terlebih dahulu dari https://getcomposer.org" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Composer ditemukan: $(composer --version | Out-String)".Trim() -ForegroundColor Green
Write-Host ""

# 1. NPM Install
Write-Host "📦 Installing npm dependencies..." -ForegroundColor Cyan
npm install
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ npm install gagal" -ForegroundColor Red
    exit 1
}
Write-Host "✅ npm dependencies installed" -ForegroundColor Green
Write-Host ""

# 2. Build Assets
Write-Host "🔨 Building Tailwind CSS & Vite assets..." -ForegroundColor Cyan
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ npm run build gagal" -ForegroundColor Red
    exit 1
}
Write-Host "✅ Assets built successfully" -ForegroundColor Green
Write-Host ""

# 3. Composer Install (Production)
Write-Host "📦 Installing PHP dependencies (production)..." -ForegroundColor Cyan
composer install --no-dev --optimize-autoloader
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ composer install gagal" -ForegroundColor Red
    exit 1
}
Write-Host "✅ PHP dependencies installed" -ForegroundColor Green
Write-Host ""

# Summary
Write-Host "==============================================="
Write-Host "✅ BUILD SELESAI - Siap untuk di-upload ke server!" -ForegroundColor Green
Write-Host "==============================================="
Write-Host ""
Write-Host "📁 Folder yang perlu di-upload ke server:" -ForegroundColor Yellow
Write-Host "  - app/"
Write-Host "  - bootstrap/"
Write-Host "  - config/"
Write-Host "  - database/"
Write-Host "  - public/  (termasuk public/build/)"
Write-Host "  - resources/"
Write-Host "  - routes/"
Write-Host "  - storage/"
Write-Host "  - vendor/"
Write-Host "  - .env"
Write-Host "  - artisan"
Write-Host "  - composer.json"
Write-Host "  - package.json"
Write-Host ""
Write-Host "📝 Ingatkan:"
Write-Host "  ✓ Pastikan .env sudah update dengan production credentials"
Write-Host "  ✓ APP_ENV=production, APP_DEBUG=false"
Write-Host "  ✓ Upload via SFTP/FTP ke cPanel"
Write-Host "  ✓ Set permissions storage & bootstrap/cache ke 755"
Write-Host ""

# Install Backend Dependencies
Write-Host "🚀 Installing Backend Dependencies..." -ForegroundColor Cyan
Set-Location -Path "backend"
npm install

# Run Database Migration
Write-Host "`n📦 Running Database Migration..." -ForegroundColor Yellow
npm run migrate

Write-Host "`n✅ Backend setup complete!" -ForegroundColor Green
Write-Host "To start backend server, run: npm run dev" -ForegroundColor White

# Go back to root
Set-Location -Path ".."

# Install Frontend Dependencies
Write-Host "`n🎨 Installing Frontend Dependencies..." -ForegroundColor Cyan
Set-Location -Path "frontend"
npm install

Write-Host "`n✅ Frontend setup complete!" -ForegroundColor Green
Write-Host "To start frontend server, run: npm run dev" -ForegroundColor White

# Go back to root
Set-Location -Path ".."

Write-Host "`n" -NoNewline
Write-Host "═══════════════════════════════════════════" -ForegroundColor Magenta
Write-Host "🎮 Lapak Gaming - Setup Complete! 🎮" -ForegroundColor Cyan
Write-Host "═══════════════════════════════════════════" -ForegroundColor Magenta
Write-Host "`n📝 Next Steps:" -ForegroundColor Yellow
Write-Host "1. Open TWO terminal windows" -ForegroundColor White
Write-Host "2. Terminal 1: cd backend && npm run dev" -ForegroundColor Green
Write-Host "3. Terminal 2: cd frontend && npm run dev" -ForegroundColor Green
Write-Host "`n🌐 URLs:" -ForegroundColor Yellow
Write-Host "   Backend:  http://localhost:5000" -ForegroundColor White
Write-Host "   Frontend: http://localhost:3000" -ForegroundColor White
Write-Host "`n👤 Default Admin:" -ForegroundColor Yellow
Write-Host "   Email:    admin@lapakgaming.neoverse.my.id" -ForegroundColor White
Write-Host "   Password: admin123" -ForegroundColor White
Write-Host "`n📖 Documentation:" -ForegroundColor Yellow
Write-Host "   README.md - Full documentation" -ForegroundColor White
Write-Host "   QUICKSTART.md - Quick start guide" -ForegroundColor White
Write-Host "   API_TESTING.md - API testing guide" -ForegroundColor White
Write-Host "`n═══════════════════════════════════════════`n" -ForegroundColor Magenta

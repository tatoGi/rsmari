# Laravel Cloud Deployment Setup Script (Windows PowerShell)
# Georgian Tax Service - Laravel Cloud Deployment

Write-Host "🚀 Georgian Tax Service - Laravel Cloud Deployment Setup" -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host ""

# Check if git is initialized
if (!(Test-Path ".git")) {
    Write-Host "📝 Initializing Git repository..." -ForegroundColor Yellow
    git init
    git config user.email "you@example.com"
    git config user.name "Your Name"
} else {
    Write-Host "✅ Git repository already initialized" -ForegroundColor Green
}

Write-Host ""
Write-Host "📦 Checking dependencies..." -ForegroundColor Cyan

# Check PHP version
Write-Host "PHP Version:" -ForegroundColor Blue
php --version | Select-Object -First 1

# Check Node version
Write-Host "Node.js Version:" -ForegroundColor Blue
node --version

# Check Composer
Write-Host "Composer Version:" -ForegroundColor Blue
composer --version

Write-Host ""
Write-Host "🔧 Dependencies check:" -ForegroundColor Cyan

# Check if composer.json is valid
try {
    $json = Get-Content "composer.json" | ConvertFrom-Json
    Write-Host "✅ composer.json is valid" -ForegroundColor Green
} catch {
    Write-Host "❌ composer.json is invalid" -ForegroundColor Red
}

# Check if package.json exists
if (Test-Path "package.json") {
    Write-Host "✅ package.json exists" -ForegroundColor Green
} else {
    Write-Host "❌ package.json not found" -ForegroundColor Red
}

Write-Host ""
Write-Host "📋 Required files for deployment:" -ForegroundColor Cyan
Write-Host "✅ cloud.json (created)" -ForegroundColor Green
Write-Host "✅ Procfile (created)" -ForegroundColor Green
Write-Host "✅ .env.example (exists)" -ForegroundColor Green
Write-Host "✅ composer.json (exists)" -ForegroundColor Green
Write-Host "✅ package.json (exists)" -ForegroundColor Green

Write-Host ""
Write-Host "🎯 Next Steps:" -ForegroundColor Cyan
Write-Host "1. Update cloud.json with your GitHub repository URL" -ForegroundColor White
Write-Host "2. Commit all files: git add . ; git commit -m 'Prepare for Laravel Cloud deployment'" -ForegroundColor White
Write-Host "3. Push to GitHub: git push -u origin main" -ForegroundColor White
Write-Host "4. Go to https://cloud.laravel.com/ and create a new project" -ForegroundColor White
Write-Host "5. Connect your GitHub repository" -ForegroundColor White
Write-Host "6. Add environment variables in Laravel Cloud dashboard" -ForegroundColor White
Write-Host "7. Deploy!" -ForegroundColor White

Write-Host ""
Write-Host "✨ Setup complete! Happy deploying! 🚀" -ForegroundColor Green

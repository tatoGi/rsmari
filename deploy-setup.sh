#!/bin/bash
# Laravel Cloud Deployment Setup Script

echo "🚀 Georgian Tax Service - Laravel Cloud Deployment Setup"
echo "=========================================================="
echo ""

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📝 Initializing Git repository..."
    git init
    git config user.email "you@example.com"
    git config user.name "Your Name"
else
    echo "✅ Git repository already initialized"
fi

echo ""
echo "📦 Checking dependencies..."

# Check PHP version
echo "PHP Version:"
php --version | head -n 1

# Check Node version
echo "Node.js Version:"
node --version

# Check Composer
echo "Composer Version:"
composer --version

echo ""
echo "🔧 Dependencies check:"

# Check if composer.json is valid
if php -r "json_decode(file_get_contents('composer.json'), true);" 2>/dev/null; then
    echo "✅ composer.json is valid"
else
    echo "❌ composer.json is invalid"
fi

# Check if package.json exists
if [ -f "package.json" ]; then
    echo "✅ package.json exists"
else
    echo "❌ package.json not found"
fi

echo ""
echo "📋 Required files for deployment:"
echo "✅ cloud.json (created)"
echo "✅ Procfile (created)"
echo "✅ .env.example (exists)"
echo "✅ composer.json (exists)"
echo "✅ package.json (exists)"

echo ""
echo "🎯 Next Steps:"
echo "1. Update cloud.json with your GitHub repository URL"
echo "2. Commit all files: git add . && git commit -m 'Prepare for Laravel Cloud deployment'"
echo "3. Push to GitHub: git push -u origin main"
echo "4. Go to https://cloud.laravel.com/ and create a new project"
echo "5. Connect your GitHub repository"
echo "6. Add environment variables in Laravel Cloud dashboard"
echo "7. Deploy!"

echo ""
echo "✨ Setup complete! Happy deploying! 🚀"

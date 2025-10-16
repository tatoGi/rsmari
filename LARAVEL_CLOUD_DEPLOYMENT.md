# 🚀 Laravel Cloud Deployment Guide

## Prerequisites

Before deploying to Laravel Cloud, you need:

1. **Laravel Cloud Account** - Sign up at https://cloud.laravel.com/
2. **GitHub Repository** - Push your code to GitHub
3. **Git installed** - For version control
4. **Laravel Cloud CLI** (optional)

---

## Step-by-Step Deployment

### **1. Prepare Your GitHub Repository**

First, initialize Git and push your project to GitHub:

```bash
# Initialize git (if not already done)
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Georgian Tax Service Integration"

# Add remote repository (replace with your GitHub repo)
git remote add origin https://github.com/yourusername/rsmari.git

# Push to GitHub
git push -u origin main
```

### **2. Configure Environment Variables**

The `cloud.json` and `Procfile` files have been created for you.

#### Key Configuration Files:
- **`cloud.json`** - Laravel Cloud configuration
- **`Procfile`** - Process types for web, worker, and scheduler
- **`composer.json`** - PHP dependencies (already configured)
- **`package.json`** - Node.js dependencies

### **3. Set Up on Laravel Cloud Dashboard**

Go to https://cloud.laravel.com/ and follow these steps:

#### **Option A: Connect GitHub Repository (Recommended)**

1. Click **"New Project"** on Laravel Cloud dashboard
2. Select **"Laravel"** as the project type
3. Authorize GitHub and select your repository (`yourusername/rsmari`)
4. Choose a **project name** (e.g., "Georgian Tax Service")
5. Select **branch**: `main` (or your deployment branch)
6. Click **"Create Project"**

#### **Option B: Manual Deployment**

1. Use **Laravel Cloud CLI** or web interface
2. Upload your code via Git connection

### **4. Configure Database**

In your Laravel Cloud dashboard:

1. Go to **Settings** → **Database**
2. Select **MySQL** (recommended)
3. Create a new database instance
4. Laravel Cloud will automatically provide connection credentials

### **5. Set Environment Variables**

In your Laravel Cloud dashboard:

1. Go to **Settings** → **Environment Variables**
2. Add the following variables:

```
APP_NAME=Georgian Tax Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.laravel.cloud
APP_KEY=[Laravel will generate this]

DB_CONNECTION=mysql
DB_HOST=[provided by Laravel Cloud]
DB_PORT=3306
DB_DATABASE=georgian_tax_service
DB_USERNAME=[provided by Laravel Cloud]
DB_PASSWORD=[provided by Laravel Cloud]

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
```

### **6. Enable Automatic Deployments**

1. Go to **Settings** → **Deployment**
2. Enable **"Automatic Deployments"** from GitHub
3. Select branch(es) to auto-deploy (e.g., `main`)
4. Every push to that branch will trigger a deployment

### **7. Monitor Deployment**

1. Go to **Deployments** tab
2. Watch the build logs in real-time
3. Logs show:
   - ✅ `composer install`
   - ✅ `npm install && npm run build`
   - ✅ `php artisan migrate --force`
   - ✅ Application startup

### **8. Post-Deployment Tasks**

After successful deployment, Laravel Cloud will automatically:

1. **Install Dependencies**
   ```
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

2. **Run Migrations**
   ```
   php artisan migrate --force
   ```

3. **Clear Cache**
   ```
   php artisan cache:clear
   php artisan config:cache
   php artisan route:cache
   ```

4. **Restart Application**

### **9. Access Your Application**

Your app will be available at:
- **Primary URL**: `https://your-app-name.laravel.cloud`
- **Custom Domain** (optional): Configure in Settings → Domains

---

## Troubleshooting

### Common Issues

**❌ Build Fails**
- Check logs: **Deployments** → **View Log**
- Ensure `composer.json` is valid
- Check Node.js package versions

**❌ Database Connection Error**
- Verify database credentials in Environment Variables
- Check if database instance is running
- Run: `php artisan tinker` to test connection

**❌ File Permissions**
- Storage directory must be writable
- Laravel Cloud handles this automatically

**❌ Missing APP_KEY**
- Laravel Cloud generates it automatically
- Or run: `php artisan key:generate`

### View Live Logs

```bash
# Using Laravel Cloud CLI (if installed)
cloud logs --app=your-app-name
```

---

## GitHub Repository Setup Checklist

✅ `.gitignore` excludes:
- `.env` (local environment only)
- `/vendor`
- `/node_modules`
- `/storage/logs`
- `/storage/framework/cache`

✅ Include these files:
- `.env.example` ✓ (created)
- `cloud.json` ✓ (created)
- `Procfile` ✓ (created)
- `composer.json` ✓ (exists)
- `package.json` ✓ (exists)
- `README.md` ✓ (exists)

---

## Performance Optimization

### For Production

1. **Enable View Caching**
   ```
   php artisan view:cache
   ```

2. **Enable Route Caching**
   ```
   php artisan route:cache
   ```

3. **Use Database Query Caching**
   - Configured in `config/database.php`

4. **Enable MySQL Query Log (Disable in Production)**
   - Set `APP_DEBUG=false`

---

## Security Checklist

- ✅ `APP_DEBUG=false` in production
- ✅ `APP_ENV=production`
- ✅ Secure APP_KEY (auto-generated)
- ✅ Database credentials encrypted
- ✅ HTTPS enforced (automatic with Laravel Cloud)
- ✅ CORS properly configured (if needed)

---

## File Upload & Storage

Your application uses file uploads. In production:

1. **Configure Storage**
   - Uploaded files stored in `/storage/app/private`
   - Use `Storage::download()` for serving files

2. **Database Exports**
   - Excel exports automatically generated
   - Stored temporarily in storage

3. **Backups**
   - Laravel Cloud provides database backups
   - Configure auto-backup schedule in Settings

---

## Next Steps

1. **Push to GitHub**: Commit and push all changes
2. **Sign Up**: Create Laravel Cloud account
3. **Connect Repository**: Link GitHub repo to Laravel Cloud
4. **Set Environment Variables**: Add DB credentials and app settings
5. **Deploy**: Watch the deployment process
6. **Test**: Visit your live URL and verify functionality
7. **Monitor**: Check logs and performance metrics

---

## Useful Links

- 🔗 Laravel Cloud Docs: https://laravel.com/docs/cloud
- 🔗 Laravel Deployment: https://laravel.com/docs/deployment
- 🔗 Your Project: https://cloud.laravel.com/

## Support

For issues:
- Check deployment logs on Laravel Cloud dashboard
- Review this guide's troubleshooting section
- Contact Laravel Cloud support: https://laravel.com/support

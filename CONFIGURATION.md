# Configuration Checklist & Deployment Guide

## ✅ Pre-Launch Checklist

### Database Setup
- [x] Migration created: `2025_10_16_130206_create_tax_payer_results_table`
- [x] Migration run: `php artisan migrate`
- [x] Table created: `tax_payer_results`
- [ ] Backup configured
- [ ] Database user created (if using MySQL)

### Dependencies
- [x] `maatwebsite/excel` installed (v3.1.67)
- [x] `guzzlehttp/guzzle` installed (v7.10+)
- [x] All dependencies: `composer install`
- [ ] Production dependencies optimized: `composer install --no-dev`

### Application Configuration
- [x] Environment file: `.env`
- [x] App key generated: `php artisan key:generate`
- [x] Excel config published: `php artisan vendor:publish`
- [ ] API endpoint verified: `https://xdata.rs.ge/TaxPayer/RSPublicInfo`
- [ ] SSL certificate configured (for HTTPS)

### Files & Permissions
- [x] All PHP files created
- [x] All Blade templates created
- [x] All migrations created
- [ ] Storage directory writable: `storage/` (755 permissions)
- [ ] Cache directory writable: `bootstrap/cache/` (755 permissions)
- [ ] Upload directory configured if needed

### Code Quality
- [x] All controllers created and tested
- [x] All models created and tested
- [x] All services created and tested
- [ ] Code style check: `php artisan pint`
- [ ] Static analysis: `phpstan` (optional)

## 🚀 Development Environment Setup

### Step 1: Initial Setup
```bash
cd "d:\rs mari\rsmari"
php artisan migrate
php artisan cache:clear
php artisan config:clear
```

### Step 2: Start Development Server
```bash
# Option A: Default port (8000)
php artisan serve

# Option B: Custom port
php artisan serve --port=8001 --host=0.0.0.0
```

### Step 3: Verify Installation
```bash
# Open browser
http://localhost:8000

# Check routes
php artisan route:list | grep taxpayer

# Test database
php artisan tinker
>>> \App\Models\TaxPayerResult::count()
```

## 📋 Environment Configuration

### `.env` File Settings

```env
APP_NAME="Georgian Tax Service"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Europe/Tbilisi

DB_CONNECTION=sqlite
# DB_DATABASE=/full/path/to/database.sqlite

# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=localhost
# DB_PORT=3306
# DB_DATABASE=tax_service_db
# DB_USERNAME=root
# DB_PASSWORD=password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=single
LOG_LEVEL=debug
```

## 🔧 Optional Configuration

### API Rate Limiting
Modify in `app/Services/RSPublicInfoService.php`:

```php
// Current: 0.5 seconds (500000 microseconds)
usleep(500000);

// Adjust as needed:
usleep(1000000); // 1 second
usleep(250000);  // 0.25 seconds
```

### Max Upload Size
In `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Or in Laravel `config/filesystems.php` if custom disk.

### Session Timeout
In `config/session.php`:
```php
'lifetime' => 120, // 2 hours
```

### Query Timeout
In `app/Services/RSPublicInfoService.php`:
```php
'timeout' => 30, // seconds
```

## 🗄️ Database Maintenance

### Backup Database
```bash
# SQLite
cp database/database.sqlite database/backup_$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u root -p tax_service_db > backup_$(date +%Y%m%d).sql
```

### Clear Old Records
```bash
php artisan tinker

# Delete records older than 90 days
>>> \App\Models\TaxPayerResult::where('created_at', '<', now()->subDays(90))->delete()

# Clear all
>>> \App\Models\TaxPayerResult::truncate()

# Check count
>>> \App\Models\TaxPayerResult::count()
```

## 📊 Monitoring & Maintenance

### View Application Logs
```bash
# Real-time
tail -f storage/logs/laravel.log

# Search for errors
grep ERROR storage/logs/laravel.log

# Search for API calls
grep "RS API" storage/logs/laravel.log

# Search for specific IdentCode
grep "206322102" storage/logs/laravel.log
```

### Check Health
```bash
php artisan tinker

# Check API connectivity
>>> $service = app(\App\Services\RSPublicInfoService::class)
>>> $result = $service->getPublicInfo('206322102')
>>> print_r($result)

# Database status
>>> \Illuminate\Support\Facades\DB::connection()->getPdo()

# Cache status
>>> \Illuminate\Support\Facades\Cache::get('test', 'works')
```

## 🔐 Security Hardening

### For Production

#### 1. Environment
```bash
APP_ENV=production
APP_DEBUG=false
```

#### 2. HTTPS
```bash
# Generate SSL certificate (Let's Encrypt)
sudo certbot certonly --standalone -d yourdomain.com
```

#### 3. Rate Limiting
Add middleware in `app/Http/Kernel.php`:
```php
protected $middleware = [
    // ...
    \App\Http\Middleware\TrustProxies::class,
];

protected $routeMiddleware = [
    // ...
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
];
```

#### 4. CORS (if API)
Install and configure: `composer require fruitcake/laravel-cors`

#### 5. API Authentication
Add API token authentication for export endpoints.

## 🚀 Production Deployment

### Using Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/rsmari/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Using Apache

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/rsmari/public

    <Directory /var/www/rsmari>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Environment Setup
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Deploy
git clone repo
composer install --no-dev
php artisan migrate --force
chmod -R 755 storage bootstrap/cache
```

## 📈 Performance Optimization

### Caching
```php
// In controller
$results = Cache::remember('taxpayer_results', 3600, function () {
    return TaxPayerResult::paginate(20);
});
```

### Database Indexing
Current indexes:
- `ident_code` (searchable)

Additional recommended:
- `response_status` (for filtering)
- `created_at` (for date range queries)

Add in migration:
```php
$table->index('response_status');
$table->index('created_at');
```

### Pagination
Already implemented at 20 results per page.
Adjustable in controller:
```php
$results->paginate(50); // More per page
```

## 🐛 Troubleshooting

### "Ports in use" Error
```bash
# Find process on port 8000
lsof -i :8000

# Kill process
kill -9 <PID>

# Or use different port
php artisan serve --port=8001
```

### Database Lock
```bash
# Remove lock file
rm database/database.sqlite-wal
rm database/database.sqlite-shm

# Or use different database driver
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Memory Issues
```php
// In artisan command
php artisan --memory=256M

// For large imports
php artisan optimize
```

## 📞 Support Contacts

- **Georgian Tax Service**: https://xdata.rs.ge/
- **Laravel Documentation**: https://laravel.com
- **PHP Documentation**: https://php.net
- **Stack Overflow Tags**: `laravel`, `php`, `guzzle`

## ✅ Final Checklist Before Going Live

- [ ] Environment variables configured
- [ ] Database backup created
- [ ] SSL certificate installed
- [ ] All permissions set correctly
- [ ] Error logging configured
- [ ] Monitoring setup
- [ ] Backup strategy documented
- [ ] API rate limits understood
- [ ] Load testing performed
- [ ] Security audit completed
- [ ] Documentation reviewed
- [ ] Team training completed

## 📋 Maintenance Schedule

### Daily
- [ ] Check error logs: `storage/logs/laravel.log`
- [ ] Monitor disk space
- [ ] Monitor API response times

### Weekly
- [ ] Database backup
- [ ] Review usage statistics
- [ ] Check for pending updates

### Monthly
- [ ] Full system audit
- [ ] Performance review
- [ ] Security patches
- [ ] Cache cleanup

### Quarterly
- [ ] Database optimization
- [ ] Full backup restoration test
- [ ] Capacity planning review

---

**Last Updated:** October 16, 2025
**Version:** 1.0.1

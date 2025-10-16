# Georgian Tax Service Integration (RSPublicInfo) - v1.0.1

A complete Laravel 12 application for integrating with the Georgian Revenue Service (RS) Public Information API. This system allows bulk processing of taxpayer IdentCodes through Excel file uploads and stores comprehensive results for export and analysis.

## 📋 Features

### Core Functionality
✅ **Excel File Import** - Upload Excel/CSV files containing multiple Georgian IdentCodes
✅ **Bulk API Processing** - Query Georgian Tax Service API for each IdentCode
✅ **Result Storage** - Automatically save all query results to database
✅ **Excel Export** - Export results back to Excel with comprehensive formatting
✅ **Single Query** - Search for individual taxpayers by IdentCode
✅ **Error Handling** - Comprehensive error tracking and reporting
✅ **Responsive UI** - Bootstrap 5 modern interface with real-time feedback

### Taxpayer Information Retrieved
- **Status** - უბნის სტატუსი (Taxpayer Status)
- **Registered Subject** - რეგისტრირებული სუბიექტი (Type of organization)
- **Full Name** - სრული სახელი (Taxpayer name)
- **Start Date** - დაწყების თარიღი (Registration date)
- **VAT Payer** - დღგ გადამხდელი (VAT registration status)
- **Mortgage** - იპოთეკა (Mortgage/Pledge information)
- **Sequestration** - ყადაღა (Legal hold information)
- **Additional Status** - დამატებითი სტატუსი (Extra status info)
- **Non-Resident** - არარეზიდენტი (Residency status)

## 🏗️ Project Structure

```
app/
├── Http/Controllers/
│   └── TaxPayerController.php       # Main controller for tax payer operations
├── Models/
│   └── TaxPayerResult.php           # Eloquent model for storing results
├── Services/
│   └── RSPublicInfoService.php      # API service for Georgian Tax Service
├── Imports/
│   └── IdentCodeImport.php          # Excel import handler
└── Exports/
    └── TaxPayerResultsExport.php    # Excel export formatter

database/
└── migrations/
    └── 2025_10_16_130206_create_tax_payer_results_table.php

resources/views/
├── welcome.blade.php                # Landing/home page
├── layouts/
│   └── app.blade.php               # Main layout template
└── taxpayer/
    ├── index.blade.php             # Results dashboard
    └── upload.blade.php            # Upload interface

routes/
└── web.php                          # API routes

config/
└── excel.php                        # Excel configuration
```

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Laravel 12
- Composer
- SQLite/MySQL database

### Installation Steps

1. **Clone/Setup Project**
```bash
cd "d:\rs mari\rsmari"
```

2. **Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate
```

3. **Database Setup**
```bash
# Run migrations
php artisan migrate
```

4. **Start Development Server**
```bash
php artisan serve --port=8000
```

Then open http://localhost:8000 in your browser.

## 📖 Usage Guide

### 1. Upload Excel File with Multiple IdentCodes

**Supported Formats:**
- Excel (.xlsx, .xls)
- CSV (.csv)
- Maximum file size: 10MB

**Excel File Structure:**

Option 1 - With Header Row:
```
IdentCode
206322102
12345678910
987654321
```

Option 2 - First Column Only:
```
206322102
12345678910
987654321
```

The system automatically detects column names: `IdentCode`, `ident_code`, `code`, `identification_code`, etc.

### 2. Single Query

If you need to check a single IdentCode, use the "Single Query" form on the results page:
- Enter a 9-digit code (legal entity) or 11-digit code (individual)
- System immediately queries the API and stores the result

### 3. View Results

The results dashboard displays:
- Statistics (Total queries, Success count, Error count, Success rate)
- Detailed table with all results
- View detailed information modal for each record
- Pagination support (20 results per page)

### 4. Export Results

Filter and export results:
- **Start Date** - Filter from specific date
- **End Date** - Filter to specific date
- **Status Filter** - All results, successful only, or errors only
- Download as formatted Excel file

### 5. Clear Results

Remove all stored results from the database (action cannot be undone).

## 🔌 API Integration

### Georgian Tax Service API

**Endpoint:** `https://xdata.rs.ge/TaxPayer/RSPublicInfo`
**Method:** POST
**Content-Type:** application/json

**Request Format:**
```json
{
  "IdentCode": "206322102"
}
```

**Response Format:**
```json
[
  {
    "Status": "არამეწარმე ფ/პ",
    "RegisteredSubject": "ინდივიდუალური მეწარმე",
    "FullName": "სატესტო სატესტო",
    "StartDate": "9/6/2019 4:53:19 PM",
    "VatPayer": "კი",
    "Mortgage": "ქონება დატვირთულია გირავნობის/იპოთეკის უფლებით",
    "Sequestration": "ქონება დაყადაղებულია",
    "AdditionalStatus": "ფიქსირებული გადასახადის გადამხდელი",
    "NonResident": "არა"
  }
]
```

### Rate Limiting

The application includes automatic rate limiting:
- 0.5 second delay between API calls
- Prevents API overload and rate limit violations
- Suitable for bulk processing

## 🛡️ Error Handling

All errors are captured and stored:
- Invalid IdentCode format
- API connection failures
- Invalid responses
- Network timeouts
- Server errors (5xx)

Each error is logged with:
- IdentCode that failed
- Error message
- Timestamp
- Raw API response (when available)

## 📊 Database Schema

### tax_payer_results Table

```sql
CREATE TABLE tax_payer_results (
  id BIGINT PRIMARY KEY,
  ident_code VARCHAR(11) INDEXED,
  status VARCHAR(255),
  registered_subject VARCHAR(255),
  full_name VARCHAR(255),
  start_date VARCHAR(255),
  vat_payer VARCHAR(255),
  mortgage TEXT,
  sequestration TEXT,
  additional_status VARCHAR(255),
  non_resident VARCHAR(255),
  response_status ENUM('success', 'error'),
  error_message TEXT,
  raw_response LONGTEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

## 🎨 User Interface

### Modern Bootstrap 5 Design
- Responsive layout (mobile, tablet, desktop)
- Dark mode support
- Real-time statistics cards
- Interactive modals
- Drag-and-drop file upload
- Auto-dismissing alerts (5 seconds)

### Routes

| Route | Method | Purpose |
|-------|--------|---------|
| `/` | GET | Landing page |
| `/taxpayer` | GET | Results dashboard |
| `/taxpayer/upload` | GET | Upload page |
| `/taxpayer/import` | POST | Process Excel import |
| `/taxpayer/single` | POST | Query single IdentCode |
| `/taxpayer/export` | GET | Export filtered results |
| `/taxpayer/clear` | DELETE | Clear all results |

## 🔒 Security Features

- CSRF protection on all forms
- Input validation on IdentCode format
- SQL injection prevention (Eloquent ORM)
- XSS protection in templates
- Secure file upload handling
- Error logging without exposing sensitive data

## 📝 Dependencies

### Core
- Laravel 12
- PHP 8.2+

### Packages
- `maatwebsite/excel` - Excel import/export functionality
- `guzzlehttp/guzzle` - HTTP client for API calls
- `laravel/tinker` - Laravel REPL (dev)

## 🔄 IdentCode Validation

**Valid IdentCode Formats:**
- 9 digits: `206322102` (Legal entities)
- 11 digits: `12345678910` (Individuals)

**Automatic Cleaning:**
- Non-digit characters automatically removed
- Leading/trailing spaces trimmed
- Duplicate entries removed

## 📈 Processing Performance

**Typical Processing Times:**
- Single query: < 2 seconds
- 100 IdentCodes: ~50 seconds
- 1000 IdentCodes: ~8-10 minutes

**Factors:**
- Network latency
- API response times
- Server performance
- Database write speed

## 🐛 Troubleshooting

### Port Already in Use
```bash
php artisan serve --port=9000
```

### Database Errors
```bash
php artisan migrate:fresh
```

### Excel Import Issues
- Check file format (.xlsx, .xls, or .csv)
- Verify IdentCode values are numeric
- Ensure file size < 10MB
- Check for column name variations

### API Connection Issues
- Verify internet connection
- Check Georgian Tax Service API status
- Review error messages in logs (`storage/logs/`)
- Verify IdentCode format (9 or 11 digits)

## 📋 Logs

All requests and errors are logged to:
```
storage/logs/laravel.log
```

View logs with:
```bash
tail -f storage/logs/laravel.log
```

## 🚀 Production Deployment

### Recommendations

1. **Environment Variables**
```bash
APP_ENV=production
APP_DEBUG=false
```

2. **Database**
- Use MySQL/PostgreSQL instead of SQLite
- Regular backups
- Connection pooling

3. **Caching**
- Enable query caching
- Use Redis for sessions

4. **Security**
- SSL/HTTPS only
- Rate limiting middleware
- API authentication

5. **Monitoring**
- Application monitoring
- Error tracking (Sentry, etc.)
- Performance monitoring

## 📞 Support & Documentation

- [Georgian Tax Service API](https://xdata.rs.ge/)
- [Laravel Documentation](https://laravel.com)
- [Laravel Excel Documentation](https://docs.laravel-excel.com)
- [Guzzle Documentation](https://docs.guzzlephp.org)

## 📄 License

This application is provided as-is for integration with Georgian Tax Service.

## 🎯 Version History

### v1.0.1 (October 16, 2025)
- Initial release
- Excel import/export functionality
- Bulk API processing
- Single query support
- Results filtering and export
- Modern responsive UI
- Complete error handling

---

**Created:** October 16, 2025
**Updated:** October 16, 2025

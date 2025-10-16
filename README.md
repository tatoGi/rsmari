# 🇬🇪 Georgian Tax Service Integration Platform

<p align="center">
  <strong>A Laravel 12 Application for Bulk Taxpayer Information Queries via Georgian Revenue Service API</strong>
</p>

<p align="center">
  <a href="#-project-overview">Overview</a> •
  <a href="#-api-integration">API</a> •
  <a href="#-features">Features</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-usage">Usage</a> •
  <a href="#-documentation">Docs</a>
</p>

---

## 🎯 Project Overview

**Georgian Tax Service Integration** is a modern web application built with Laravel 12 that allows organizations to:

- ✅ Upload Excel files containing multiple Georgian IdentCodes (Personal/Business ID numbers)
- ✅ Query the Georgian Revenue Service (RS) Public Information API for each taxpayer
- ✅ Store comprehensive results in a database
- ✅ Export results back to Excel with formatting
- ✅ Perform single taxpayer lookups
- ✅ Track upload order and batch information

### Project Type
**Bulk Data Processing Platform** - Enterprise-grade tax information management system

### Use Cases
- Tax compliance verification
- Business intelligence gathering
- Bulk taxpayer status verification
- Data analysis and reporting
- Government business records integration

---

## 🔌 API Integration

### Georgian Revenue Service (RS) - RSPublicInfo API

**API Endpoint:** `https://xdata.rs.ge/TaxPayer/RSPublicInfo`

**Purpose:** Query public taxpayer information from Georgian Revenue Service database

**API Type:** REST API (POST requests)

**Authentication:** Public API (no authentication required)

#### Request Format
```json
{
  "IdentCode": "12345678901"
}
```

#### Response Format
```json
{
  "Status": "Active",
  "RegisteredSubject": "Individual",
  "FullName": "John Doe",
  "StartDate": "2020-01-15",
  "VatPayer": "Yes",
  "Mortgage": "No",
  "Sequestration": "No",
  "AdditionalStatus": "Standard",
  "NonResident": "No"
}
```

#### Data Retrieved
| Field | Georgian | Description |
|-------|----------|-------------|
| **Status** | უბნის სტატუსი | Current taxpayer status (Active/Inactive) |
| **RegisteredSubject** | რეგისტრირებული სუბიექტი | Organization type |
| **FullName** | სრული სახელი | Taxpayer name |
| **StartDate** | დაწყების თარიღი | Registration date |
| **VatPayer** | დღგ გადამხდელი | VAT registration status |
| **Mortgage** | იპოთეკა | Mortgage/pledge status |
| **Sequestration** | ყადაღა | Legal hold/sequestration status |
| **AdditionalStatus** | დამატებითი სტატუსი | Additional status information |
| **NonResident** | არარეზიდენტი | Non-resident taxpayer status |

#### API Configuration
- **Base URL:** `https://xdata.rs.ge/TaxPayer`
- **Method:** POST
- **Content-Type:** application/json
- **Timeout:** 30 seconds
- **Error Handling:** Comprehensive retry and logging

**Service Class:** `app/Services/RSPublicInfoService.php`

---

## ✨ Features

### 1. **Excel Import** 📥
- Upload Excel/CSV files with multiple IdentCodes
- Automatic row position tracking (preserves upload order)
- Multiple column name support (ident_code, personal_number, code, etc.)
- Batch processing with unique session IDs
- Error reporting for invalid codes

### 2. **Bulk API Processing** 🔄
- Process multiple taxpayer queries in sequence
- API rate limiting (0.5s delay between requests)
- Comprehensive error handling and logging
- Success/failure status tracking
- Raw response storage for debugging

### 3. **Result Storage** 💾
- All results stored in database
- Batch tracking for audit trails
- Upload order preservation (maintains original file order)
- Timestamp tracking for each query
- Error message storage

### 4. **Excel Export** 📤
- Export results to formatted Excel file
- Maintains original upload order
- Professional styling (headers, borders, colors)
- Georgian/English column headers
- Auto-sized columns
- Sortable date formatting

### 5. **Single Query** 🔍
- Query individual taxpayers by IdentCode
- Immediate results display
- Real-time API response
- Manual data entry

### 6. **Data Management** 🗂️
- Filter results by date range
- Filter by response status (success/error)
- Pagination for large datasets
- Batch grouping and filtering
- Upload history tracking

---

## 🏗️ Technical Architecture

### Stack
- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Database:** SQLite/MySQL/PostgreSQL
- **Frontend:** Bootstrap 5
- **Excel:** Maatwebsite/Laravel-Excel 3.1
- **HTTP Client:** Guzzle 7.x

### Project Structure
```
app/
├── Http/Controllers/
│   └── TaxPayerController.php          # Main application logic
├── Models/
│   └── TaxPayerResult.php              # Result storage model
├── Services/
│   └── RSPublicInfoService.php         # API integration service
├── Imports/
│   └── IdentCodeImport.php             # Excel import handler
└── Exports/
    └── TaxPayerResultsExport.php       # Excel export formatter

database/
└── migrations/
    ├── ..._create_tax_payer_results_table.php
    └── ..._add_source_data_to_tax_payer_results.php

resources/views/
├── welcome.blade.php                   # Landing page
├── layouts/app.blade.php               # Main layout
└── taxpayer/
    ├── index.blade.php                 # Results dashboard
    └── upload.blade.php                # Upload interface

routes/
└── web.php                             # Application routes

config/
└── excel.php                           # Excel configuration
```

---

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite, MySQL, or PostgreSQL
- Git

### Setup Steps

1. **Clone or Extract Project**
```bash
cd "d:\rs mari\rsmari"
```

2. **Install PHP Dependencies**
```bash
composer install
```

3. **Install Frontend Dependencies**
```bash
npm install
npm run build
```

4. **Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

5. **Database Setup**
```bash
# Run migrations
php artisan migrate
```

6. **Start Development Server**
```bash
# Option 1: PHP Built-in Server
php artisan serve

# Option 2: Vite Dev Server (in separate terminal)
npm run dev
```

**Access Application:** http://localhost:8000

---

## 📖 Usage

### Upload Excel File

1. Navigate to `/taxpayer` page
2. Click "Upload Excel"
3. Select file with IdentCodes
4. System automatically:
   - Generates unique batch ID
   - Tracks row positions
   - Queries API for each code
   - Stores results in database

### Export Results

1. Go to Results Dashboard
2. Optional filters: Date range, status
3. Click "Export Results"
4. Download Excel file with:
   - Results in original upload order
   - Professional formatting
   - Complete tax information

### Single Query

1. Go to Results Dashboard
2. Enter IdentCode in search form
3. View immediate results
4. Results stored for future export

---

## 📊 Database Schema

### tax_payer_results Table
```sql
- id (Primary Key)
- ident_code (VARCHAR)
- status (VARCHAR)
- registered_subject (VARCHAR)
- full_name (VARCHAR)
- start_date (DATE)
- vat_payer (VARCHAR)
- mortgage (VARCHAR)
- sequestration (VARCHAR)
- additional_status (VARCHAR)
- non_resident (VARCHAR)
- response_status (VARCHAR) - 'success' or 'error'
- error_message (TEXT)
- raw_response (JSON)
- name (VARCHAR) - Source data
- user (VARCHAR) - Source data
- gift_name (VARCHAR) - Source data
- upload_order (INT) - Position in original file
- upload_batch_id (VARCHAR) - Batch identifier
- created_at, updated_at (TIMESTAMPS)
```

---

## 🔐 Security

- ✅ CSRF protection on all forms
- ✅ Input validation for IdentCodes
- ✅ Error messages don't expose sensitive data
- ✅ API responses logged securely
- ✅ Database queries use parameterized queries
- ✅ SSL verification enabled (production)

---

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| `GEORGIAN_TAX_SERVICE_README.md` | Detailed feature documentation |
| `UPLOAD_ORDER_PRESERVATION.md` | Technical implementation of order tracking |
| `LARAVEL_CLOUD_DEPLOYMENT.md` | Production deployment guide |
| `GETTING_STARTED.md` | Quick start guide |
| `API_TESTING.md` | API endpoint testing guide |

---

## 🧪 Testing

### Manual Testing
```bash
# Test with sample IdentCode
# Use: 12345678901 (valid Georgian format: 9 or 11 digits)
```

### API Testing
See `API_TESTING.md` for comprehensive testing guide

---

## 🚀 Deployment

### Laravel Cloud Deployment
See `LARAVEL_CLOUD_DEPLOYMENT.md` for complete deployment instructions

**Quick Steps:**
1. Visit https://cloud.laravel.com/
2. Connect GitHub repository
3. Configure environment variables
4. Deploy

### Local Production Server
```bash
# Optimize application
php artisan optimize
php artisan config:cache
php artisan route:cache

# Start server
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 📈 Performance

- **Batch Processing:** Handles 100+ IdentCodes per upload
- **API Response Time:** ~500ms per query (0.5s delay between requests)
- **Database Queries:** Indexed for fast filtering
- **Export Time:** < 2 seconds for 1000 records

---

## 🐛 Troubleshooting

### API Connection Issues
- Check internet connection
- Verify `https://xdata.rs.ge/TaxPayer/RSPublicInfo` is accessible
- Check logs: `storage/logs/laravel.log`

### Excel Upload Issues
- Verify file format (xlsx, xls, csv)
- Ensure IdentCodes are in recognized columns
- Check file size (max 10MB)

### Database Issues
- Verify migrations ran: `php artisan migrate:status`
- Check database credentials in `.env`

---

## 📞 Support

- 📖 See comprehensive documentation files
- 🔍 Check `storage/logs/laravel.log` for errors
- 📝 Review `API_TESTING.md` for API examples

---

## 📄 License

This project is licensed under the MIT License - see LICENSE file for details.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit issues or pull requests.

---

## 📞 Contact

For questions or support, please refer to the documentation files included in this project.

---

**Built with ❤️ using Laravel 12 & Georgian Revenue Service API**

*Last Updated: October 16, 2025*
*Version: 1.0*

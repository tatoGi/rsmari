# Quick Start Guide - Georgian Tax Service Integration

## 🚀 5-Minute Setup

### Step 1: Database Migration
```bash
php artisan migrate
```

### Step 2: Start the Server
```bash
php artisan serve
```

Or with a specific port:
```bash
php artisan serve --port=8000
```

### Step 3: Open in Browser
Navigate to: **http://localhost:8000**

You should see the beautiful landing page with options to:
- 📊 View Results
- 📤 Upload Excel
- 🔍 Search Single IdentCode

## 📋 Quick Usage

### Method 1: Upload Excel File

1. Click "Upload Excel" button
2. Drag & drop or select your Excel/CSV file
3. File should contain IdentCodes (9 or 11 digits)
4. Click "Upload and Process"
5. Wait for processing to complete
6. View results on dashboard

### Method 2: Single Query

1. Go to Results page
2. Enter a single IdentCode in "Single Query" form
3. Click "Search"
4. See immediate results

### Method 3: Export Results

1. Go to Results page
2. Click "Export Results" button
3. Select filters (optional):
   - Start Date
   - End Date
   - Status (Success/Error/All)
4. Click "Export to Excel"
5. File downloads automatically

## 📁 Sample Excel File Format

Create an Excel file with this format:

```
IdentCode
206322102
12345678910
987654321
123456789
98765432101
```

Or use any column name like: `ident_code`, `code`, `identification_code`, etc.

## 📊 What Information You'll Get

For each IdentCode, the system retrieves:

| Field | Example |
|-------|---------|
| Status | არამეწარმე ფ/პ |
| Registered Subject | ინდივიდუალური მეწარმე |
| Full Name | სატესტო სატესტო |
| Start Date | 9/6/2019 4:53:19 PM |
| VAT Payer | კი / არა |
| Mortgage | ქონება დატვირთულია... |
| Sequestration | ქონება დაყადაღებულია |
| Additional Status | ფიქსირებული გადასახადის... |
| Non-Resident | კი / არა |

## 🎯 Real Example

### Example: Query 3 Taxpayers

1. **Create Excel file** (`taxpayers.xlsx`):
```
IdentCode
206322102
12345678910
987654321
```

2. **Upload file:**
   - Go to `/taxpayer/upload`
   - Select file
   - Click "Upload and Process"

3. **System processes:**
   - Reads 3 IdentCodes
   - Queries Georgian Tax Service API
   - Stores results (success or error)
   - Shows: "3 successful queries, 0 errors"

4. **View results:**
   - Go to `/taxpayer`
   - See table with 3 rows
   - Each row has full taxpayer info

5. **Export results:**
   - Click "Export Results"
   - Download formatted Excel file
   - Share or analyze data

## 🔍 Available Routes

```
GET   /                      → Home/Landing page
GET   /taxpayer              → Results Dashboard
GET   /taxpayer/upload       → Upload Form
POST  /taxpayer/import       → Process Upload
POST  /taxpayer/single       → Query Single
GET   /taxpayer/export       → Export Results
DELETE /taxpayer/clear       → Clear All (needs confirmation)
```

## ⚙️ Configuration

### API Settings
- **API Endpoint:** `https://xdata.rs.ge/TaxPayer/RSPublicInfo`
- **Rate Limit:** 0.5 seconds between calls
- **Timeout:** 30 seconds per request

### File Upload Settings
- **Max File Size:** 10MB
- **Accepted Formats:** .xlsx, .xls, .csv
- **Location:** Processed in memory (secure)

### Database Settings
- **Table:** `tax_payer_results`
- **Storage:** SQLite (can change to MySQL/PostgreSQL)
- **Retention:** All results kept until manually cleared

## 🐛 Common Issues & Solutions

### Issue: "Failed to listen on 127.0.0.1:8000"
**Solution:**
```bash
# Try different port
php artisan serve --port=8001
```

### Issue: "No results showing"
**Solution:**
- Run migration: `php artisan migrate`
- Check database: `sqlite3 database/database.sqlite`

### Issue: "Upload file not working"
**Solution:**
- Verify file format (.xlsx, .xls, or .csv)
- Check file size < 10MB
- Ensure IdentCodes are numeric

### Issue: "API connection error"
**Solution:**
- Check internet connection
- Verify API status: https://xdata.rs.ge
- Check IdentCode format (9 or 11 digits)

## 📊 Understanding the Dashboard

### Statistics Cards
- **Total Queries:** All processed IdentCodes
- **Successful:** Queries with valid responses
- **Errors:** Failed queries
- **Success Rate:** Percentage of successful queries

### Results Table
Shows all query results with:
- IdentCode
- Taxpayer Status
- Full Name
- Registered Subject
- VAT Payer Status
- Additional Status
- Query Success/Error badge
- Processing date/time
- View details button

### Result Details Modal
Click "View" to see:
- All taxpayer information fields
- Raw API response (JSON format)
- Error message (if failed)

## 🚀 Next Steps

### For Single Queries
- Use "Single Query" form for 1-5 taxpayer lookups
- Instant results
- No file upload needed

### For Bulk Processing
- Prepare Excel file with many IdentCodes
- Upload via "Upload Excel"
- System processes automatically
- Export results when done

### For Data Analysis
- Filter results by date range
- Filter by success/error status
- Export to Excel for further analysis
- Use in your reports

## 💡 Pro Tips

1. **Organize Data:** Keep Excel files with meaningful names (date, purpose)
2. **Batch Processing:** Process files in batches of 100-500 for optimal performance
3. **Regular Exports:** Export results regularly for backup
4. **Check Logs:** View `storage/logs/laravel.log` for detailed debugging
5. **Rate Limiting:** System auto-handles API rate limiting (0.5s delays)

## 📞 Need Help?

1. **Check Logs:** `storage/logs/laravel.log`
2. **Verify Format:** Excel file must have IdentCodes in first column
3. **Test Single Query:** Try a single IdentCode first
4. **API Status:** Check Georgian Tax Service API availability
5. **Database:** Ensure migrations ran successfully

## 🎉 Success Indicators

✅ You're ready when:
- [ ] Server running without errors
- [ ] Can load http://localhost:8000
- [ ] Can upload Excel file
- [ ] Results appear in database
- [ ] Can export results to Excel
- [ ] Single query works
- [ ] Error handling works

---

**Enjoy using the Georgian Tax Service Integration!** 🇬🇪

For detailed documentation, see `GEORGIAN_TAX_SERVICE_README.md`

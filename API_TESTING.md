# API Testing Examples

## 🔗 Georgian Tax Service API Direct Testing

### Direct API Call (cURL)

```bash
# Test single IdentCode
curl -X POST https://xdata.rs.ge/TaxPayer/RSPublicInfo \
  -H "Content-Type: application/json" \
  -d '{"IdentCode":"206322102"}'
```

### Using PHP (Directly)

```php
$client = new \GuzzleHttp\Client();

$response = $client->post('https://xdata.rs.ge/TaxPayer/RSPublicInfo', [
    'json' => [
        'IdentCode' => '206322102'
    ]
]);

$data = json_decode($response->getBody(), true);
print_r($data);
```

## 🧪 Application API Testing

### 1. Single Query Endpoint

**POST** `/taxpayer/single`

**Parameters:**
```
ident_code: "206322102"
```

**Using cURL:**
```bash
curl -X POST http://localhost:8000/taxpayer/single \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "X-CSRF-TOKEN: <csrf_token>" \
  -d "ident_code=206322102"
```

**Using PHP:**
```php
$ch = curl_init('http://localhost:8000/taxpayer/single');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['ident_code' => '206322102']);
$response = curl_exec($ch);
```

### 2. View Results

**GET** `/taxpayer`

**Using cURL:**
```bash
curl http://localhost:8000/taxpayer
```

### 3. Export Results

**GET** `/taxpayer/export`

**Query Parameters:**
```
?start_date=2025-10-01
?end_date=2025-10-31
?status=success
```

**Using cURL:**
```bash
curl "http://localhost:8000/taxpayer/export?status=success" \
  -o results.xlsx
```

### 4. Upload Excel File

**POST** `/taxpayer/import`

**Using cURL:**
```bash
curl -X POST http://localhost:8000/taxpayer/import \
  -H "X-CSRF-TOKEN: <csrf_token>" \
  -F "excel_file=@taxpayers.xlsx"
```

**Using PHP:**
```php
$client = new \GuzzleHttp\Client();

$response = $client->post('http://localhost:8000/taxpayer/import', [
    'multipart' => [
        [
            'name'     => 'excel_file',
            'contents' => fopen('taxpayers.xlsx', 'r')
        ]
    ]
]);

echo $response->getBody();
```

## 📊 Expected Response Examples

### Successful Query

```json
[
  {
    "Status": "არამეწარმე ფ/პ",
    "RegisteredSubject": "ინდივიდუალური მეწარმე",
    "FullName": "სატესტო სატესტო",
    "StartDate": "9/6/2019 4:53:19 PM",
    "VatPayer": "კი",
    "Mortgage": "ქონება დატვირთულია გირავნობის/იპოთეკის უფლებით",
    "Sequestration": "ქონება დაყადაღებულია",
    "AdditionalStatus": "ფიქსირებული გადასახადის გადამხდელი",
    "NonResident": "არა"
  }
]
```

### Error Response

```json
{
  "success": false,
  "error": "Invalid IdentCode format",
  "raw_response": null
}
```

## 🔄 Bulk Processing Example

### Python Script

```python
import requests
import pandas as pd
import time

# Read Excel file
df = pd.read_excel('taxpayers.xlsx')

# API endpoint
url = 'https://xdata.rs.ge/TaxPayer/RSPublicInfo'

results = []

for idx, row in df.iterrows():
    ident_code = str(row['IdentCode']).strip()
    
    if not ident_code:
        continue
    
    try:
        response = requests.post(
            url,
            json={'IdentCode': ident_code},
            timeout=30
        )
        
        if response.status_code == 200:
            data = response.json()
            if isinstance(data, list) and len(data) > 0:
                results.append({
                    'ident_code': ident_code,
                    'status': 'success',
                    'data': data[0]
                })
            else:
                results.append({
                    'ident_code': ident_code,
                    'status': 'error',
                    'error': 'Empty response'
                })
        else:
            results.append({
                'ident_code': ident_code,
                'status': 'error',
                'error': f'HTTP {response.status_code}'
            })
    
    except Exception as e:
        results.append({
            'ident_code': ident_code,
            'status': 'error',
            'error': str(e)
        })
    
    # Rate limiting
    time.sleep(0.5)

# Save results
results_df = pd.DataFrame(results)
results_df.to_excel('results.xlsx', index=False)
print(f"Processed {len(results)} records")
```

## 🛠️ Debugging & Testing

### Check Database Records

```bash
php artisan tinker

# Count all results
>>> \App\Models\TaxPayerResult::count()

# See successful queries
>>> \App\Models\TaxPayerResult::where('response_status', 'success')->count()

# See errors
>>> \App\Models\TaxPayerResult::where('response_status', 'error')->get()

# Search specific IdentCode
>>> \App\Models\TaxPayerResult::where('ident_code', '206322102')->first()
```

### Monitor Logs

```bash
# Real-time log watching
tail -f storage/logs/laravel.log

# Search for API errors
grep "RS API" storage/logs/laravel.log

# Search specific IdentCode
grep "206322102" storage/logs/laravel.log
```

### Test API Service Directly

```bash
php artisan tinker

>>> $service = app(\App\Services\RSPublicInfoService::class)
>>> $result = $service->getPublicInfo('206322102')
>>> print_r($result)
```

## 📈 Performance Testing

### Load Test with Apache Bench

```bash
# Single query test
ab -n 100 -c 10 http://localhost:8000/taxpayer

# Export test
ab -n 50 -c 5 "http://localhost:8000/taxpayer/export?status=success"
```

### With Curl (Sequential)

```bash
# Test 100 queries sequentially
for i in {1..100}; do
  ident_code=$((200000000 + i))
  curl -X POST http://localhost:8000/taxpayer/single \
    -d "ident_code=$ident_code" \
    --silent -o /dev/null
  sleep 1
done
```

## 🔐 Security Testing

### CSRF Protection

```bash
# This will fail without CSRF token
curl -X POST http://localhost:8000/taxpayer/single \
  -d "ident_code=206322102"

# This will work with CSRF token
curl -X POST http://localhost:8000/taxpayer/single \
  -H "X-CSRF-TOKEN: <token>" \
  -d "ident_code=206322102"
```

### Input Validation

```bash
# Invalid IdentCode (too short)
curl -X POST http://localhost:8000/taxpayer/single \
  -d "ident_code=123"

# Invalid IdentCode (non-numeric)
curl -X POST http://localhost:8000/taxpayer/single \
  -d "ident_code=abc123def"

# Empty IdentCode
curl -X POST http://localhost:8000/taxpayer/single \
  -d "ident_code="
```

## 📋 Sample Test Data

### Valid 9-Digit Codes (Legal Entities)
```
206322102
123456789
555555555
999999999
111111111
```

### Valid 11-Digit Codes (Individuals)
```
12345678910
98765432101
11111111111
55555555555
99999999999
```

## 🚀 Automated Testing (Artisan Command)

Create a custom command:

```bash
php artisan make:command TestTaxPayer
```

Then in `app/Console/Commands/TestTaxPayer.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RSPublicInfoService;
use App\Models\TaxPayerResult;

class TestTaxPayer extends Command
{
    protected $signature = 'taxpayer:test {--count=10}';
    protected $description = 'Test API with sample data';

    public function handle(RSPublicInfoService $service)
    {
        $count = $this->option('count');
        $testCodes = ['206322102', '12345678910', '987654321'];
        
        for ($i = 0; $i < $count; $i++) {
            $code = $testCodes[$i % count($testCodes)];
            $this->line("Testing: $code");
            $service->getPublicInfo($code);
        }
        
        $this->info("Tested $count queries");
    }
}
```

Run with:
```bash
php artisan taxpayer:test --count=50
```

## 📚 Additional Resources

- [Georgian Tax Service API](https://xdata.rs.ge/)
- [Guzzle HTTP Client](https://docs.guzzlephp.org)
- [Laravel API Documentation](https://laravel.com/api/)
- [Excel Package Documentation](https://docs.laravel-excel.com)

---

**Last Updated:** October 16, 2025

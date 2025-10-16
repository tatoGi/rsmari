<?php

namespace App\Http\Controllers;

use App\Services\RSPublicInfoService;
use App\Imports\IdentCodeImport;
use App\Exports\TaxPayerResultsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TaxPayerResult;

class TaxPayerController extends Controller
{
    protected $rsService;

    public function __construct(RSPublicInfoService $rsService)
    {
        $this->rsService = $rsService;
    }

    public function index()
    {
        $results = TaxPayerResult::latest()->paginate(20);
        return view('taxpayer.index', compact('results'));
    }

    public function upload()
    {
        return view('taxpayer.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            // Import Excel file to get IdentCodes
            $import = new IdentCodeImport();
            Excel::import($import, $request->file('excel_file'));

            $identCodes = $import->getIdentCodes();
            $rowData = $import->getRowData();
            $uploadBatchId = $import->getUploadBatchId();
            $results = [];
            $errors = [];

            // Process each IdentCode
            foreach ($identCodes as $identCode) {
                if (empty($identCode)) continue;

                try {
                    $response = $this->rsService->getPublicInfo($identCode);

                    if ($response['success']) {
                        // Get source data for this IdentCode
                        $sourceData = $rowData[$identCode] ?? [];

                        // Store successful result
                        $result = TaxPayerResult::create([
                            'ident_code' => $identCode,
                            'status' => $response['data']['Status'] ?? '',
                            'registered_subject' => $response['data']['RegisteredSubject'] ?? '',
                            'full_name' => $response['data']['FullName'] ?? '',
                            'start_date' => $response['data']['StartDate'] ?? '',
                            'vat_payer' => $response['data']['VatPayer'] ?? '',
                            'mortgage' => $response['data']['Mortgage'] ?? '',
                            'sequestration' => $response['data']['Sequestration'] ?? '',
                            'additional_status' => $response['data']['AdditionalStatus'] ?? '',
                            'non_resident' => $response['data']['NonResident'] ?? '',
                            'response_status' => 'success',
                            'raw_response' => json_encode($response['data']),
                            'name' => $sourceData['name'] ?? '',
                            'user' => $sourceData['user'] ?? '',
                            'gift_name' => $sourceData['gift_name'] ?? '',
                            'upload_order' => $sourceData['upload_order'] ?? 0,
                            'upload_batch_id' => $uploadBatchId,
                        ]);
                        $results[] = $result;
                    } else {
                        // Get source data for this IdentCode
                        $sourceData = $rowData[$identCode] ?? [];

                        // Store error result
                        $result = TaxPayerResult::create([
                            'ident_code' => $identCode,
                            'response_status' => 'error',
                            'error_message' => $response['error'] ?? 'Unknown error',
                            'raw_response' => json_encode($response),
                            'name' => $sourceData['name'] ?? '',
                            'user' => $sourceData['user'] ?? '',
                            'gift_name' => $sourceData['gift_name'] ?? '',
                            'upload_order' => $sourceData['upload_order'] ?? 0,
                            'upload_batch_id' => $uploadBatchId,
                        ]);
                        $errors[] = $identCode . ': ' . ($response['error'] ?? 'Unknown error');
                    }

                    // Add small delay to avoid overwhelming the API
                    usleep(500000); // 0.5 second delay
                } catch (\Exception $e) {
                    $sourceData = $rowData[$identCode] ?? [];
                    $errors[] = $identCode . ': ' . $e->getMessage();

                    // Store exception result
                    TaxPayerResult::create([
                        'ident_code' => $identCode,
                        'response_status' => 'error',
                        'error_message' => $e->getMessage(),
                        'raw_response' => null,
                        'name' => $sourceData['name'] ?? '',
                        'user' => $sourceData['user'] ?? '',
                        'gift_name' => $sourceData['gift_name'] ?? '',
                        'upload_order' => $sourceData['upload_order'] ?? 0,
                        'upload_batch_id' => $uploadBatchId,
                    ]);
                }
            }

            $message = 'Processing completed. ';
            $message .= count($results) . ' successful queries, ';
            $message .= count($errors) . ' errors.';

            if (!empty($errors)) {
                $message .= ' Errors: ' . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= ' and ' . (count($errors) - 5) . ' more...';
                }
            }

            return redirect()->route('taxpayer.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');

        $query = TaxPayerResult::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status) {
            $query->where('response_status', $status);
        }

        $filename = 'taxpayer_results_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new TaxPayerResultsExport($query), $filename);
    }

    public function single(Request $request)
    {
        $request->validate([
            'ident_code' => 'required|string|min:9|max:11'
        ]);

        try {
            $identCode = $request->ident_code;
            $response = $this->rsService->getPublicInfo($identCode);

            if ($response['success']) {
                // Store successful result
                $result = TaxPayerResult::create([
                    'ident_code' => $identCode,
                    'status' => $response['data']['Status'] ?? '',
                    'registered_subject' => $response['data']['RegisteredSubject'] ?? '',
                    'full_name' => $response['data']['FullName'] ?? '',
                    'start_date' => $response['data']['StartDate'] ?? '',
                    'vat_payer' => $response['data']['VatPayer'] ?? '',
                    'mortgage' => $response['data']['Mortgage'] ?? '',
                    'sequestration' => $response['data']['Sequestration'] ?? '',
                    'additional_status' => $response['data']['AdditionalStatus'] ?? '',
                    'non_resident' => $response['data']['NonResident'] ?? '',
                    'response_status' => 'success',
                    'raw_response' => json_encode($response['data'])
                ]);

                return redirect()->route('taxpayer.index')->with('success', 'Successfully retrieved information for: ' . $identCode);
            } else {
                // Store error result
                TaxPayerResult::create([
                    'ident_code' => $identCode,
                    'response_status' => 'error',
                    'error_message' => $response['error'] ?? 'Unknown error',
                    'raw_response' => json_encode($response)
                ]);

                return redirect()->back()->with('error', 'Failed to retrieve information: ' . ($response['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function clear()
    {
        TaxPayerResult::truncate();
        return redirect()->route('taxpayer.index')->with('success', 'All results have been cleared.');
    }
}

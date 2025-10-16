<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;

class IdentCodeImport implements ToCollection, WithHeadingRow
{
    protected $identCodes = [];
    protected $rowData = [];

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            // Try to find IdentCode in different possible column names
            $identCode = null;

            // Check various possible column names
            $possibleColumns = [
                'personal_number', 'ident_code', 'ident', 'code',
                'identification_code', 'tax_number', 'taxpayer_id',
                'საიდენტიფიკაციო_კოდი', 'კოდი'
            ];

            foreach ($possibleColumns as $column) {
                if (isset($row[$column]) && !empty($row[$column])) {
                    $identCode = $this->cleanIdentCode($row[$column]);
                    break;
                }
            }

            // If no named column found, try the first column
            if (!$identCode && $row->isNotEmpty()) {
                $firstValue = $row->first();
                if (!empty($firstValue)) {
                    $identCode = $this->cleanIdentCode($firstValue);
                }
            }

            // Validate and add if valid
            if ($identCode && $this->isValidIdentCode($identCode)) {
                $this->identCodes[] = $identCode;

                // Store additional data from the row
                $this->rowData[$identCode] = [
                    'name' => $row['name'] ?? $row['Name'] ?? '',
                    'user' => $row['user'] ?? $row['User'] ?? '',
                    'gift_name' => $row['gift_name'] ?? $row['Gift_name'] ?? '',
                ];
            }
        }
    }

    public function getIdentCodes(): array
    {
        return array_unique($this->identCodes);
    }

    public function getRowData(): array
    {
        return $this->rowData;
    }    private function cleanIdentCode($value): string
    {
        // Convert to string and remove any non-digit characters
        $cleaned = preg_replace('/\D/', '', (string) $value);
        return $cleaned;
    }

    private function isValidIdentCode(string $identCode): bool
    {
        // Georgian IdentCode should be 9 or 11 digits
        return preg_match('/^\d{9}$|^\d{11}$/', $identCode);
    }
}

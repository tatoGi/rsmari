<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Database\Eloquent\Builder;

class TaxPayerResultsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        // Order by upload_order to maintain the original file order
        // Then by upload_batch_id to group uploads together
        return $this->query->orderBy('upload_batch_id', 'desc')
                          ->orderBy('upload_order', 'asc');
    }

    public function headings(): array
    {
        return [
            'საიდენტიფიკაციო კოდი (IdentCode)',
            'სტატუსი (Status)',
            'რეგისტრირებული სუბიექტი (Registered Subject)',
            'სრული სახელი (Full Name)',
            'დაწყების თარიღი (Start Date)',
            'დღგ გადამხდელი (VAT Payer)',
            'იპოთეკა (Mortgage)',
            'ყადაღა (Sequestration)',
            'დამატებითი სტატუსი (Additional Status)',
            ' romersიდენტი (Non-Resident)',
            'მოთხოვნის სტატუსი (Request Status)',
            'შეცდომის შეტყობინება (Error Message)',
            'სახელი (Name)',
            'მომხმარებელი (User)',
            'საჩუქრის კატეგორია (Gift Name)',
            'დამუშავების თარიღი (Processed Date)'
        ];
    }

    public function map($result): array
    {
        return [
            $result->ident_code,
            $result->status ?? '',
            $result->registered_subject ?? '',
            $result->full_name ?? '',
            $result->start_date ?? '',
            $result->vat_payer ?? '',
            $result->mortgage ?? '',
            $result->sequestration ?? '',
            $result->additional_status ?? '',
            $result->non_resident ?? '',
            $result->response_status,
            $result->error_message ?? '',
            $result->name ?? '',
            $result->user ?? '',
            $result->gift_name ?? '',
            $result->created_at ? $result->created_at->format('Y-m-d H:i:s') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E6E6FA'
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            // Style for data rows
            'A2:M1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}

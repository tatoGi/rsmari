<?php

/**
 * This file creates a sample Excel file for download
 * Place this in your routes if you want a direct download endpoint
 */

// Usage: Add to your controller or create a new route

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleIdentCodesExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['206322102'],
            ['12345678910'],
            ['987654321'],
            ['123456789'],
            ['98765432101'],
            ['555555555'],
            ['11111111111'],
            ['99999999999'],
            ['77777777'],
            ['44444444444'],
        ];
    }

    public function headings(): array
    {
        return ['IdentCode'];
    }
}

// To use, add this route to routes/web.php:
// Route::get('/taxpayer/sample', function () {
//     return Excel::download(new SampleIdentCodesExport(), 'sample_identcodes.xlsx');
// })->name('taxpayer.sample');

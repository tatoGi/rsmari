<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxPayerController;

Route::get('/', function () {
    return view('welcome');
});

// Tax Payer Routes
Route::prefix('taxpayer')->name('taxpayer.')->group(function () {
    Route::get('/', [TaxPayerController::class, 'index'])->name('index');
    Route::get('/upload', [TaxPayerController::class, 'upload'])->name('upload');
    Route::post('/import', [TaxPayerController::class, 'import'])->name('import');
    Route::post('/single', [TaxPayerController::class, 'single'])->name('single');
    Route::get('/export', [TaxPayerController::class, 'export'])->name('export');
    Route::delete('/clear', [TaxPayerController::class, 'clear'])->name('clear');
});

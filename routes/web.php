<?php

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;   // <-- Import File
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

Route::get('/pdf', function () {
    $invoice = Invoice::find(2);
    return view('invoice', ['invoice' => $invoice]);
});
Route::get('/pdf/{pdf}', function ($id) {
    $invoice = Invoice::find($id);
    $name = Str::replace('/', '-', $invoice->number) . '.pdf';
    return Pdf::loadView('invoice', ['invoice' => $invoice])->setPaper('a4', 'portrait')
        ->stream($name);
})->name('invoices.previewPdf');

<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/exports/sample-pdf', [ExportController::class, 'samplePdf'])
    ->name('exports.sample-pdf');

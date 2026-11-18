<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/exports/sample-pdf', [ExportController::class, 'samplePdf'])
    ->name('exports.sample-pdf');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/accounts')
    ->name('admin.accounts.')
    ->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/', [AccountController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AccountController::class, 'update'])->name('update');
        Route::patch('/{user}/toggle-active', [AccountController::class, 'toggleActive'])->name('toggle-active');
        Route::patch('/{user}/reset-password', [AccountController::class, 'resetPassword'])->name('reset-password');
    });

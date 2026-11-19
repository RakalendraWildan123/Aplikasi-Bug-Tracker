<?php

use App\Http\Controllers\AccountProfileController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin,developer'])->group(function () {
    Route::get('/', function () {
        return response()->json([
            'name' => config('app.name', 'Aplikasi Bug Tracker'),
            'description' => 'Aplikasi Monitoring & Manajemen IT Developer API',
            'status' => 'online',
        ]);
    })->name('home');

    Route::get('/account', [AccountProfileController::class, 'edit'])->name('account.edit');
    Route::put('/account/profile', [AccountProfileController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountProfileController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/exports/sample-pdf', [ExportController::class, 'samplePdf'])
        ->name('exports.sample-pdf');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/accounts')
    ->name('admin.accounts.')
    ->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/', [AccountController::class, 'store'])->name('store');
        Route::get('/{user}', [AccountController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AccountController::class, 'update'])->name('update');
        Route::patch('/{user}/toggle-active', [AccountController::class, 'toggleActive'])->name('toggle-active');
        Route::patch('/{user}/reset-password', [AccountController::class, 'resetPassword'])->name('reset-password');
        Route::delete('/{user}', [AccountController::class, 'destroy'])->name('destroy');
    });

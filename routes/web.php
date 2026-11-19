<?php

use App\Http\Controllers\AccountProfileController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('account.edit');
    })->name('home');

    Route::get('/account', [AccountProfileController::class, 'edit'])->name('account.edit');
    Route::post('/account/verify-password', [AccountProfileController::class, 'verifyPassword'])->name('account.verify-password');
    Route::put('/account/profile', [AccountProfileController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountProfileController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/kanban', [\App\Http\Controllers\KanbanController::class, 'index'])->name('kanban.index');
    Route::put('/kanban/tasks/{task}', [\App\Http\Controllers\KanbanController::class, 'update'])->name('kanban.tasks.update');
    Route::patch('/kanban/tasks/{task}/status', [\App\Http\Controllers\KanbanController::class, 'updateStatus'])->name('kanban.tasks.update-status');

    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/takeover', [\App\Http\Controllers\TaskController::class, 'takeover'])->name('tasks.takeover');
    Route::post('/kanban/tasks/{task}/takeover', [\App\Http\Controllers\TaskController::class, 'takeover'])->name('kanban.tasks.takeover');

    Route::get('/bugs', [\App\Http\Controllers\BugController::class, 'index'])->name('bugs.index');
    Route::post('/bugs', [\App\Http\Controllers\BugController::class, 'store'])->name('bugs.store');
    Route::get('/bugs/{bug}', [\App\Http\Controllers\BugController::class, 'show'])->name('bugs.show');
    Route::put('/bugs/{bug}', [\App\Http\Controllers\BugController::class, 'update'])->name('bugs.update');
    Route::delete('/bugs/{bug}', [\App\Http\Controllers\BugController::class, 'destroy'])->name('bugs.destroy');
    Route::post('/bugs/{bug}/takeover', [\App\Http\Controllers\BugController::class, 'takeover'])->name('bugs.takeover');

    // Master Proyek / CRUD Proyek
    Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
    Route::put('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');

    // Master Akun / CRUD Akun (Khusus Atasan / Admin)
    Route::middleware('role:admin')
        ->prefix('admin/accounts')
        ->name('admin.accounts.')
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AccountController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\AccountController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\AccountController::class, 'store'])->name('store');
            Route::get('/{user}', [\App\Http\Controllers\Admin\AccountController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [\App\Http\Controllers\Admin\AccountController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\Admin\AccountController::class, 'update'])->name('update');
            Route::patch('/{user}/toggle-active', [\App\Http\Controllers\Admin\AccountController::class, 'toggleActive'])->name('toggle-active');
            Route::patch('/{user}/reset-password', [\App\Http\Controllers\Admin\AccountController::class, 'resetPassword'])->name('reset-password');
            Route::delete('/{user}', [\App\Http\Controllers\Admin\AccountController::class, 'destroy'])->name('destroy');
        });

    Route::get('/exports/sample-pdf', [\App\Http\Controllers\ExportController::class, 'samplePdf'])->name('exports.sample-pdf');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

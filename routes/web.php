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

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

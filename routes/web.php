<?php

use App\Http\Controllers\AccountProfileController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::model('release', \App\Models\RepoHistory::class);
Route::model('evaluation', \App\Models\AppEvaluation::class);
Route::model('actionItem', \App\Models\ActionItem::class);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

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

    // Riwayat Rilis & Repo Modul
    Route::get('/releases', [\App\Http\Controllers\RepoHistoryController::class, 'index'])->name('releases.index');
    Route::post('/releases', [\App\Http\Controllers\RepoHistoryController::class, 'store'])->name('releases.store');
    Route::get('/releases/{release}', [\App\Http\Controllers\RepoHistoryController::class, 'show'])->name('releases.show');
    Route::put('/releases/{release}', [\App\Http\Controllers\RepoHistoryController::class, 'update'])->name('releases.update');
    Route::delete('/releases/{release}', [\App\Http\Controllers\RepoHistoryController::class, 'destroy'])->name('releases.destroy');

    // Evaluasi Aplikasi & Rencana Tindak Lanjut
    Route::get('/evaluations', [\App\Http\Controllers\AppEvaluationController::class, 'index'])->name('evaluations.index');
    Route::post('/evaluations', [\App\Http\Controllers\AppEvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/evaluations/{evaluation}', [\App\Http\Controllers\AppEvaluationController::class, 'show'])->name('evaluations.show');
    Route::put('/evaluations/{evaluation}', [\App\Http\Controllers\AppEvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/evaluations/{evaluation}', [\App\Http\Controllers\AppEvaluationController::class, 'destroy'])->name('evaluations.destroy');

    Route::post('/evaluations/{evaluation}/action-items', [\App\Http\Controllers\AppEvaluationController::class, 'storeActionItem'])->name('evaluations.action-items.store');
    Route::patch('/evaluations/action-items/{actionItem}/status', [\App\Http\Controllers\AppEvaluationController::class, 'updateActionItemStatus'])->name('evaluations.action-items.update-status');
    Route::delete('/evaluations/action-items/{actionItem}', [\App\Http\Controllers\AppEvaluationController::class, 'destroyActionItem'])->name('evaluations.action-items.destroy');

    // Laporan Periodik
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/csv', [\App\Http\Controllers\ReportController::class, 'exportCsv'])->name('reports.export.csv');

    // Notifikasi Pop-up
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');

    // Master Proyek / CRUD Proyek
    Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
    Route::put('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');

    // Master Modul / CRUD Modul
    Route::get('/modules', [\App\Http\Controllers\ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules', [\App\Http\Controllers\ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}', [\App\Http\Controllers\ModuleController::class, 'show'])->name('modules.show');
    Route::put('/modules/{module}', [\App\Http\Controllers\ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [\App\Http\Controllers\ModuleController::class, 'destroy'])->name('modules.destroy');

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

if (app()->environment('local')) {
    Route::get('/preview-dashboard', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();
        auth()->login($user);
        return app(\App\Http\Controllers\DashboardController::class)->index($request);
    });

    Route::get('/preview-kanban', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();
        auth()->login($user);
        return app(\App\Http\Controllers\KanbanController::class)->index($request);
    });
}

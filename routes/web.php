<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\BorrowingLogController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserManagementController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

    Route::resource('archives', ArchiveController::class)->parameters([
        'archives' => 'archive',
    ]);
    Route::get('/archives/{archive:uuid}/download', [ArchiveController::class, 'download'])
        ->name('archives.download')
        ->middleware('signed');

    Route::get('/borrowings', [BorrowingLogController::class, 'index'])->name('borrowings.index');
    Route::post('/archives/{archive:uuid}/borrow', [BorrowingLogController::class, 'borrow'])->name('borrowings.borrow');
    Route::patch('/borrowings/{borrowingLog}/return', [BorrowingLogController::class, 'returnArchive'])->name('borrowings.return');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::middleware('role:superadmin')->group(function () {
        Route::resource('divisions', DivisionController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('locations', LocationController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('users', UserManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/ajax/user-transactions', [DashboardController::class, 'paginateUserTransactions'])->name('dashboard.ajax.user.transactions');
    Route::get('/dashboard/ajax/user-payments', [DashboardController::class, 'paginateUserPayments'])->name('dashboard.ajax.user.payments');
    Route::get('/dashboard/ajax/admin-transactions', [DashboardController::class, 'paginateAdminTransactions'])->name('dashboard.ajax.admin.transactions');
    Route::get('/dashboard/ajax/admin-monthly', [DashboardController::class, 'paginateAdminMonthly'])->name('dashboard.ajax.admin.monthly');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    
    
    Route::middleware('admin')->group(function () {
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    });
    });
});

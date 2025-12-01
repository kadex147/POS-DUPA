<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    
    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        // Dashboard route
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Route untuk cetak laporan
        Route::get('/dashboard/print', [DashboardController::class, 'printReport'])->name('dashboard.print');

        // Transaction routes
        Route::get('/transaction-details/{transaction}', [DashboardController::class, 'getTransactionDetails'])
             ->name('transaction.details');
        
        Route::delete('/transactions/{id}', [DashboardController::class, 'deleteTransaction'])
             ->name('transactions.destroy');
        
        // Product stock routes
        Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])
            ->name('products.updateStock');
        Route::post('/pos/check-stock', [POSController::class, 'checkStock'])
            ->name('pos.checkStock');
        
        // Resource routes
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);
    });
    
    // Kasir Routes
    Route::middleware(['role:kasir'])->group(function () {
        // Dashboard khusus kasir
        Route::get('/kasir/dashboard', [DashboardController::class, 'kasirDashboard'])->name('kasir.dashboard');
        
        // Transaction details untuk kasir
        Route::get('/kasir/transaction-details/{transaction}', [DashboardController::class, 'kasirTransactionDetails'])
             ->name('kasir.transaction.details');
    });
    
    // POS Routes (Bisa diakses Admin & Kasir)
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store');
});
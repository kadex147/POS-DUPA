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
        
        // Product stock routes
        Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])
            ->name('products.updateStock');
        
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
        
        // Route untuk cetak laporan kasir
        Route::get('/kasir/dashboard/print', [DashboardController::class, 'printKasirReport'])->name('kasir.dashboard.print');
    });
    
    // ==============================================================
    // TRANSACTION ROUTES (Resource Controller Pattern) 
    // Bisa diakses oleh Admin & Kasir
    // ==============================================================
    
    // POS/Transaction Resource Routes
    // Using RESTful Resource Controller pattern for better organization
    Route::prefix('transactions')->name('transactions.')->group(function () {
        // GET /transactions - Display POS page with products
        Route::get('/', [POSController::class, 'index'])->name('index');
        
        // POST /transactions - Create new transaction (checkout)
        Route::post('/', [POSController::class, 'store'])->name('store');
        
        // GET /transactions/{id} - Show transaction details (for modal)
        Route::get('/{id}', [POSController::class, 'show'])->name('show');
        
        // DELETE /transactions/{id} - Delete transaction (Admin only, handled in controller)
        Route::delete('/{id}', [POSController::class, 'destroy'])->name('destroy');
        
        // POST /transactions/check-stock - Check product stock availability (AJAX)
        Route::post('/check-stock', [POSController::class, 'checkStock'])->name('checkStock');
    });
    
    // Backward compatibility - Keep old POS routes pointing to new transaction routes
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store');
    Route::post('/pos/check-stock', [POSController::class, 'checkStock'])->name('pos.checkStock');
    
    // Transaction details route (untuk modal) - backward compatibility
    Route::get('/transaction-details/{transaction}', [POSController::class, 'show'])
         ->name('transaction.details');
});
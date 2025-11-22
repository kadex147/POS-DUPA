<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    
    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        // Ini adalah rute dashboard yang benar (menggunakan controller)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- RUTE BARU UNTUK MODAL DETAIL ---
        Route::get('/transaction-details/{transaction}', [DashboardController::class, 'getTransactionDetails'])
             ->name('transaction.details');
                    // Route untuk update stock product
        Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])
            ->name('products.updateStock');

        // Route untuk check stock (optional, untuk AJAX check)
        Route::post('/pos/check-stock', [POSController::class, 'checkStock'])
            ->name('pos.checkStock');
        // --- BATAS RUTE BARU ---
        
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);
        Route::resource('transaction', UserController::class);
    });
    
    // POS Routes (Bisa diakses Admin & Kasir)
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store');
});
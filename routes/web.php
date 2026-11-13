<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}/codes', [\App\Http\Controllers\ProductCodeController::class, 'show'])->name('products.codes');
    Route::get('/products/{product}/codes/qr', [\App\Http\Controllers\ProductCodeController::class, 'qrCode'])->name('products.codes.qr');
    Route::get('/products/{product}/codes/barcode', [\App\Http\Controllers\ProductCodeController::class, 'barcode'])->name('products.codes.barcode');
    Route::get('/stock', [\App\Http\Controllers\StockMovementController::class, 'index'])->name('stock.index');
    Route::post('/stock', [\App\Http\Controllers\StockMovementController::class, 'store'])->name('stock.store');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/users/register', [\App\Http\Controllers\UserRegistrationController::class, 'create'])->name('users.register');
        Route::post('/users/register', [\App\Http\Controllers\UserRegistrationController::class, 'store'])->name('users.register.store');
        Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    });
});

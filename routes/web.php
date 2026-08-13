<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginFrom'])->name('login');

Route::middleware(['token.auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/categories', [DashboardController::class, 'category'])->name('categories');
    Route::get('/products', [DashboardController::class, 'product'])->name('products');
    Route::get('/stocks', [DashboardController::class, 'stock'])->name('stocks');
    Route::get('/pos', [DashboardController::class, 'pos'])->name('pos');
    Route::get('/invoices', [DashboardController::class, 'invoice'])->name('invoices');
});

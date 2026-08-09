<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginFrom'])->name('login');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/categories', [DashboardController::class, 'category'])->name('categories');
Route::get('/products', [DashboardController::class, 'product'])->name('products');
Route::get('/stocks', [DashboardController::class, 'stock'])->name('stocks');
Route::get('/pos', [DashboardController::class, 'pos'])->name('pos');
Route::get('/invoices', [DashboardController::class, 'invoice'])->name('invoices');

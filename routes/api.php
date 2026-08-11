<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Category\CategoryController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\Invoice\InvoiceController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Stock\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function(){
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

        // Dashboard Routes
        Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

        // Category Routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Product Routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Stock Routes
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::post('/stocks', [StockController::class, 'stockIn'])->name('stocks.in');
        Route::post('/stocks/adjustment', [StockController::class, 'stockAdjustment'])->name('stocks.adjustment');

        // Invoice Routes
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    });
});

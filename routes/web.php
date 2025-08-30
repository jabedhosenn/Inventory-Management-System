<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [UserController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/addcategory', [AdminController::class, 'addCategory'])->name('admin.addcategory');
    Route::post('/addcategory', [AdminController::class, 'createCategory'])->name('admin.createaddcategory');
    Route::get('/viewcategory', [AdminController::class, 'viewCategory'])->name('admin.viewcategory');
    Route::get('/editcategory/{id}', [AdminController::class, 'editCategory'])->name('admin.editcategory');
    Route::post('/editcategory/{id}', [AdminController::class, 'updateCategory'])->name('admin.updatecategory');
    Route::delete('/deletecategory/{id}', [AdminController::class, 'deleteCategory'])->name('admin.deletecategory');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

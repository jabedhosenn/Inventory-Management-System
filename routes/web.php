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
    Route::get('/addsupplier', [AdminController::class, 'addSupplier'])->name('admin.addsupplier');
    Route::post('/addsupplier', [AdminController::class, 'createSupplier'])->name('admin.createsupplier');
    Route::get('/viewsupplier', [AdminController::class, 'viewSupplier'])->name('admin.viewsupplier');
    Route::get('/editsupplier/{id}', [AdminController::class, 'editSupplier'])->name('admin.editsupplier');
    Route::post('/editsupplier/{id}', [AdminController::class, 'updateSupplier'])->name('admin.updatesupplier');
    Route::delete('/deletesupplier/{id}', [AdminController::class, 'deleteSupplier'])->name('admin.deletesupplier');
    Route::get('/addproduct', [AdminController::class, 'addProduct'])->name('admin.addproduct');
    Route::post('/addproduct', [AdminController::class, 'createProduct'])->name('admin.createproduct');
    Route::get('/viewproduct', [AdminController::class, 'viewProduct'])->name('admin.viewproduct');
    Route::get('/editproduct/{id}', [AdminController::class, 'editProduct'])->name('admin.editproduct');
    Route::post('/updateproduct/{id}', [AdminController::class, 'updateProduct'])->name('admin.updateproduct');
    Route::delete('/deleteproduct/{id}', [AdminController::class, 'deleteProduct'])->name('admin.deleteproduct');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔐 Auth Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authLogin', [LoginController::class, 'authLogin'])->name('authLogin');

// 🔒 Protected Routes (hanya bisa diakses setelah login)
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('role:1')->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
        Route::resource('categories', CategoryController::class);
        Route::resource('transactions', TransactionController::class);
        Route::resource('users', UserController::class);
    });

    Route::middleware('role:2')->group(function () {
        Route::get('kasir/products', [ProductController::class, 'index'])->name('kasir.products.index');
        Route::get('kasir/transactions', [TransactionController::class, 'index'])->name('kasir.transactions.index');
        Route::post('kasir/transactions', [TransactionController::class, 'store'])->name('kasir.transactions.store');
    });

    Route::middleware('role:3')->group(function () {
        Route::get('pimpinan/products', [ProductController::class, 'index'])->name('pimpinan.products.index');
        Route::get('pimpinan/laporan', [PimpinanController::class, 'index'])->name('pimpinan.laporan');
    });
});


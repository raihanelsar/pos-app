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
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // 📌 Dashboard umum
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    // DataTables
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');

    // Role 1: Admin
    Route::middleware('role:1')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
    });

    // Role 2: Kasir
    Route::middleware('role:2')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');
        Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    });

    // Role 3: Pimpinan
    Route::middleware('role:3')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/laporan', [TransactionController::class, 'laporan'])->name('pimpinan.laporan');
        Route::get('/laporan/{id}', [TransactionController::class, 'detailLaporan'])->name('detailLaporan');
    });
});

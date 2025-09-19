<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [LoginController::class,'login'])->name('login');
Route::post('/authLogin', [LoginController::class,'authLogin'])->name('authLogin');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');

// Dashboard (semua user login bisa akses)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class,'index'])->name('dashboard');
});

// Shared route: admin & kasir sama-sama bisa akses
Route::middleware(['auth','role:admin|kasir'])->group(function () {
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->group(function () {
    // Products & Categories CRUD penuh
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // Transactions full
    Route::resource('transactions', TransactionController::class);

    // (opsional) Kelola user
    // Route::view('users', 'users.index')->name('users.index');
});

/*
|--------------------------------------------------------------------------
| KASIR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:kasir'])->group(function () {
    // Kasir hanya boleh lihat produk (index & show)
    Route::resource('products', ProductController::class)->only(['index','show']);
    // Transaksi hanya kasir
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
});


/*
|--------------------------------------------------------------------------
| PIMPINAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:pimpinan'])->group(function () {
    // Lihat produk (stok barang)
    Route::resource('products', ProductController::class)->only(['index','show']);

    // Laporan penjualan

});

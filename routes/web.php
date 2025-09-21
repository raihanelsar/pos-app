<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;


// 🔐 Auth Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authLogin', [LoginController::class, 'authLogin'])->name('authLogin');

// 🔒 Protected Routes (hanya bisa diakses setelah login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password', [UserController::class, 'editPassword'])->name('password.edit');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');

    // Role 1: Admin
    Route::middleware('role:1')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
        return view('dashboard.dashboard-admin');})->name('dashboard');
        Route::resource('products', ProductController::class)->except(['edit']);
        // Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
        Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
    });

    // Role 2: Kasir
    Route::middleware('role:2')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', function () {
        return view('dashboard.dashboard-kasir');
    })->name('dashboard');
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    });

    // Role 3: Pimpinan
    Route::middleware('role:3')->prefix('pimpinan')->name('pimpinan.')->group(function () {
         Route::get('/dashboard', function () {
        return view('dashboard.dashboard-pimpinan');
    })->name('dashboard');
        Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/laporan', [TransactionController::class, 'laporan'])->name('pimpinan.laporan');
        Route::get('/laporan/{id}', [TransactionController::class, 'detailLaporan'])->name('detailLaporan');
    });
});

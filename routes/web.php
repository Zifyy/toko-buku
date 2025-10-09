<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DetailBukuController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

// 🏠 Halaman utama diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🔑 AUTH ROUTES
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 🛠️ ADMIN ROUTES (Role: admin)
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD Buku
    Route::resource('buku', BukuController::class);

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);

    // CRUD Detail Buku
    Route::resource('detail-buku', DetailBukuController::class);

    // CRUD User
    Route::resource('user', UserController::class);

    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
});

// 📊 OWNER ROUTES (Role: owner)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('owner.dashboard');
});

// 🧾 KASIR ROUTES (Role: kasir)
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    // Dashboard utama kasir (daftar buku)
    Route::get('/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');

    // Transaksi
    Route::post('/transaksi', [KasirController::class, 'transaksi'])->name('kasir.transaksi');
    Route::post('/transaksi/tambah', [KasirController::class, 'tambah'])->name('kasir.transaksi.tambah');
    Route::post('/transaksi/hapus/{rowId}', [KasirController::class, 'hapus'])->name('kasir.transaksi.hapus');
    Route::post('/transaksi/finalize', [KasirController::class, 'finalize'])->name('kasir.transaksi.finalize');
    Route::get('/transaksi', [KasirController::class, 'showTransaksi'])->name('kasir.transaksi');
});

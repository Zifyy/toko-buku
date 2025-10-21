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

// 🏠 Redirect ke login
Route::get('/', fn() => redirect()->route('login'));

// 🔑 AUTH ROUTES
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 🛠️ ADMIN ROUTES (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Buku
    Route::resource('buku', BukuController::class);

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);

    // CRUD Detail Buku
    Route::resource('detail-buku', DetailBukuController::class);

    // CRUD User
    Route::resource('user', UserController::class);

    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporanKeuangan'])->name('laporan');
});

// 🔧 ALIAS ROUTES (untuk backward compatibility dengan blade files yang lama)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Alias untuk buku tanpa prefix admin
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Alias untuk kategori tanpa prefix admin
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Alias untuk detail-buku tanpa prefix admin
    Route::get('/detail-buku', [DetailBukuController::class, 'index'])->name('detail-buku.index');
    Route::get('/detail-buku/create', [DetailBukuController::class, 'create'])->name('detail-buku.create');
    Route::post('/detail-buku', [DetailBukuController::class, 'store'])->name('detail-buku.store');
    Route::get('/detail-buku/{detail_buku}', [DetailBukuController::class, 'show'])->name('detail-buku.show');
    Route::get('/detail-buku/{detail_buku}/edit', [DetailBukuController::class, 'edit'])->name('detail-buku.edit');
    Route::put('/detail-buku/{detail_buku}', [DetailBukuController::class, 'update'])->name('detail-buku.update');
    Route::delete('/detail-buku/{detail_buku}', [DetailBukuController::class, 'destroy'])->name('detail-buku.destroy');

    // Alias untuk user tanpa prefix admin
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

// 📊 OWNER ROUTES (Role: owner)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/', [OwnerController::class, 'dashboard'])->name('dashboard');

    // Data Buku (Read Only)
    Route::get('/buku', [OwnerController::class, 'buku'])->name('buku');
    Route::get('/data-buku', [OwnerController::class, 'buku'])->name('data_buku'); // Alias

    // Data User - CRUD lengkap
    Route::get('/user', [OwnerController::class, 'user'])->name('user');
    Route::get('/data-user', [OwnerController::class, 'user'])->name('data_user'); // Alias
    Route::get('/user/create', [OwnerController::class, 'createUser'])->name('user.create');
    Route::post('/user', [OwnerController::class, 'storeUser'])->name('user.store');
    Route::get('/user/{id}/edit', [OwnerController::class, 'editUser'])->name('user.edit');
    Route::put('/user/{id}', [OwnerController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/{id}', [OwnerController::class, 'destroyUser'])->name('user.destroy');

    // 📈 Laporan
    Route::get('/laporan', [OwnerController::class, 'laporan'])->name('laporan');

    // ✅ Export laporan
    Route::get('/laporan/export', [OwnerController::class, 'exportLaporan'])->name('laporan.export');

    // Detail Transaksi (opsional)
    Route::get('/transaksi', [OwnerController::class, 'transaksi'])->name('transaksi');
});

// 🧾 KASIR ROUTES (Role: kasir)
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');

    // Proses kirim cart ke session
    Route::post('/transaksi', [KasirController::class, 'storeTransaction'])->name('transaksi.store');

    // Tampilkan halaman checkout
    Route::get('/transaksi', [KasirController::class, 'showTransaksi'])->name('transaksi');

    // Simpan ke database (final)
    Route::post('/transaksi/finalize', [KasirController::class, 'finalize'])->name('transaksi.finalize');

    // Tampilkan nota
    Route::get('/transaksi/nota/{id}', [KasirController::class, 'nota'])->name('transaksi.nota');

    // Riwayat transaksi
    Route::get('/riwayat', [TransaksiController::class, 'riwayat'])->name('riwayat');
});
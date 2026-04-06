<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\UserController; 

/*
|--------------------------------------------------------------------------
| Web Routes - BIBLIOX DIGITAL LIBRARY
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. GUEST ROUTES (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 3. GROUP AUTHENTICATED
Route::middleware(['auth'])->group(function () {

    // --- KHUSUS ADMIN ---
    Route::middleware(['peran:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Buku
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');           
        Route::get('/buku/tambah', [BukuController::class, 'create'])->name('buku.create');   
        Route::post('/buku/simpan', [BukuController::class, 'store'])->name('buku.store');    
        Route::get('/buku/edit/{id}', [BukuController::class, 'edit'])->name('buku.edit');    
        Route::put('/buku/update/{id}', [BukuController::class, 'update'])->name('buku.update'); 
        Route::delete('/buku/hapus/{id}', [BukuController::class, 'destroy'])->name('buku.destroy'); 

        // Verifikasi Peminjaman
        Route::get('/verifikasi', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/verifikasi/setujui/{id}', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::post('/verifikasi/tolak/{id}', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        Route::post('/verifikasi/kembali-final/{id}', [PeminjamanController::class, 'verifikasiKembali'])->name('peminjaman.verifikasi-kembali');
        Route::post('/verifikasi/bayar-denda/{id}', [PeminjamanController::class, 'bayarDenda'])->name('peminjaman.bayar-denda');

        // Manajemen Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/admin/users/{pengenal}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // --- KHUSUS ANGGOTA / SISWA ---
    Route::middleware(['peran:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
        
        // Dashboard Anggota
        Route::get('/dashboard', [DashboardController::class, 'anggota'])->name('dashboard');

        // Pinjam Buku
        Route::post('/pinjam/{id}', [BukuController::class, 'pinjam'])->name('pinjam');
        
        // Buku Saya & Pengembalian
        Route::get('/buku-saya', [PeminjamanController::class, 'bukuSaya'])->name('buku-saya');
        Route::post('/buku-saya/ajukan-kembali/{id}', [PeminjamanController::class, 'ajukanPengembalian'])->name('peminjaman.ajukan-kembali');
    });
});
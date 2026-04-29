<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\PeminjamanUserController;

// Jika user membuka halaman utama '/', mengarahkan langsung ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Route untuk menampilkan halaman login
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Route untuk memproses data saat tombol "Masuk" ditekan
Route::post('/login', [AuthController::class, 'login']);

// Route untuk proses logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk user yang sudah berhasil login
Route::middleware('auth')->group(function () {
    
    // Dashboard Utama untuk User (Siswa, Guru, Tendik)
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('user.dashboard');

    // FITUR UTAMA USER
    Route::get('/user/peminjaman', [PeminjamanUserController::class, 'index'])->name('user.peminjaman.index');

    // PEMINJAMAN BARANG TERCATAT
    Route::get('/user/peminjaman/tercatat', [PeminjamanUserController::class, 'createTercatat'])->name('user.peminjaman.tercatat');

    // PEMINJAMAN BARANG UMUM (TANPA SURAT)
    Route::get('/user/peminjaman/umum', [App\Http\Controllers\PeminjamanUserController::class, 'formUmum'])->name('user.peminjaman.umum');
    Route::post('/user/peminjaman/umum', [App\Http\Controllers\PeminjamanUserController::class, 'storeUmum'])->name('user.peminjaman.umum.store');

    // Rute Pengembalian Barang Umum (Tanpa Surat)
    Route::get('/user/pengembalian', [App\Http\Controllers\PeminjamanUserController::class, 'formPengembalian'])->name('user.pengembalian');
    Route::post('/user/pengembalian/{id}', [App\Http\Controllers\PeminjamanUserController::class, 'prosesPengembalian'])->name('user.pengembalian.proses');
    
    // POST
    Route::post('/user/peminjaman/tercatat/simpan', [PeminjamanUserController::class, 'storeTercatat'])->name('user.peminjaman.tercatat.store');    

    // RIWAYAT SURAT USER
    Route::get('/user/riwayat', [App\Http\Controllers\PeminjamanUserController::class, 'riwayat'])->name('user.riwayat');

    // ------------------------------------------------------------------------------------------------------------------------------

    // !!! Dashboard untuk Teknisi
        Route::get('/teknisi/dashboard', [DashboardController::class, 'teknisiIndex'])->name('teknisi.dashboard'); 

    // Rute Kelola User untuk Teknisi
    Route::get('/teknisi/users', [UserController::class, 'index'])->name('teknisi.users.index');
    Route::get('/teknisi/users/create', [UserController::class, 'create'])->name('teknisi.users.create');
    Route::post('/teknisi/users', [UserController::class, 'store'])->name('teknisi.users.store');

    // Rute Cetak Password
    Route::get('/teknisi/users/unduh-password-pdf', [UserController::class, 'unduhPasswordPdf'])->name('teknisi.users.pdf-password');

    // Rute Hapus Data:
    Route::delete('/teknisi/users/{id}', [UserController::class, 'destroy'])->name('teknisi.users.destroy');
    
    // Rute Reset Password
    Route::post('/teknisi/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('teknisi.users.reset-password');

    // Rute Edit Data
    Route::get('/teknisi/users/{id}/edit', [UserController::class, 'edit'])->name('teknisi.users.edit');
    Route::put('/teknisi/users/{id}', [UserController::class, 'update'])->name('teknisi.users.update');

    // Rute Import Excel  
    Route::post('/teknisi/users/import', [UserController::class, 'importExcel'])->name('teknisi.users.import');

    // Rute Kenaikan Kelas Massal
    Route::get('/teknisi/users/kenaikan-kelas', [UserController::class, 'formKenaikan'])->name('teknisi.users.kenaikan');
    Route::post('/teknisi/users/kenaikan-kelas', [UserController::class, 'prosesKenaikan'])->name('teknisi.users.proses-kenaikan');

    // Rute untuk Manajemen Barang
    Route::get('/teknisi/barang', [BarangController::class, 'index'])->name('teknisi.barang.index'); // INI BARU
    Route::get('/teknisi/barang/tambah', [BarangController::class, 'create'])->name('teknisi.barang.create');
    Route::post('/teknisi/barang/tambah', [BarangController::class, 'store'])->name('teknisi.barang.store');
    Route::post('/teknisi/kategori/simpan', [KategoriController::class, 'store'])->name('teknisi.kategori.store');
 
    // --- EDIT DAN HAPUS BARANG ---
    Route::get('/teknisi/barang/{id}/edit', [BarangController::class, 'edit'])->name('teknisi.barang.edit');
    Route::put('/teknisi/barang/{id}', [BarangController::class, 'update'])->name('teknisi.barang.update');
    Route::delete('/teknisi/barang/{id}', [BarangController::class, 'destroy'])->name('teknisi.barang.destroy');
    
    // --- Fitur Peminjaman (Teknisi) ---
    Route::get('/teknisi/peminjaman', [App\Http\Controllers\PeminjamanTeknisiController::class, 'index'])->name('teknisi.peminjaman.index');
    Route::get('/teknisi/peminjaman/{id}', [App\Http\Controllers\PeminjamanTeknisiController::class, 'show'])->name('teknisi.peminjaman.show');

     // VERIFIKASI TEKNISI ---
    Route::post('/teknisi/peminjaman/{id}/verifikasi', [App\Http\Controllers\PeminjamanTeknisiController::class, 'verifikasi'])->name('teknisi.peminjaman.verifikasi');
    
    // TAMBAH BARANG DI FORM VERIFIKASI ---
    Route::post('/teknisi/peminjaman/{id}/tambah-barang', [App\Http\Controllers\PeminjamanTeknisiController::class, 'tambahBarang'])->name('teknisi.peminjaman.tambah_barang');
    Route::delete('/teknisi/peminjaman/{id}/hapus-barang/{barang_id}', [App\Http\Controllers\PeminjamanTeknisiController::class, 'hapusBarang'])->name('teknisi.peminjaman.hapus_barang');

    // TOLAK SURAT FORM VERIFIKASI ----
    Route::post('/teknisi/peminjaman/{id}/tolak', [App\Http\Controllers\PeminjamanTeknisiController::class, 'tolak'])->name('teknisi.peminjaman.tolak');

    // --- Rute Fitur Lanjutan Teknisi ---
    Route::get('/teknisi/pengumuman', [PengumumanController::class, 'index'])->name('teknisi.pengumuman.index');
    Route::post('/teknisi/pengumuman/simpan', [PengumumanController::class, 'store'])->name('teknisi.pengumuman.store');
    Route::get('/teknisi/sop', [SopController::class, 'index'])->name('teknisi.sop.index');
    Route::post('/teknisi/sop/simpan', [SopController::class, 'store'])->name('teknisi.sop.store');

    // ---------------------------------------------------------------------------------------------------------------

    // Dashboard untuk Koordinator
    Route::get('/koordinator/dashboard', function () {
        return view('koordinator.dashboard');  
    });

    // Rute Import RFID khusus Koordinator
    Route::post('/teknisi/users/import-rfid', [UserController::class, 'importRfid'])->name('teknisi.users.import-rfid');

    // Dashboard untuk Koordinator
    Route::get('/koordinator/dashboard', [DashboardController::class, 'koordinatorIndex'])->name('koordinator.dashboard');

    // RUTE MENARA PEMANTAU KOORDINATOR
    Route::get('/koordinator/peminjaman', [App\Http\Controllers\PeminjamanKoordinatorController::class, 'index'])->name('koordinator.peminjaman.index');
    Route::post('/koordinator/peminjaman/{id}/keluarkan', [App\Http\Controllers\PeminjamanKoordinatorController::class, 'keluarkanBarang'])->name('koordinator.peminjaman.keluarkan');
    Route::post('/koordinator/peminjaman/{id}/kembalikan', [App\Http\Controllers\PeminjamanKoordinatorController::class, 'terimaKembali'])->name('koordinator.peminjaman.kembalikan');

    // --- Rute Rekapitulasi Sistem ---
    // Bisa diakses Teknisi & Koordinator
    Route::get('/rekapitulasi', [App\Http\Controllers\RekapitulasiController::class, 'index'])->name('koordinator.rekapitulasi.index');
    Route::post('/rekapitulasi/laporan', [App\Http\Controllers\RekapitulasiController::class, 'unduhLaporan'])->name('koordinator.rekapitulasi.laporan');
    
    // HANYA Koordinator 
    Route::delete('/rekapitulasi/{id}', [App\Http\Controllers\RekapitulasiController::class, 'destroy'])->name('koordinator.rekapitulasi.destroy');

});
<?php

use App\Http\Controllers\Anggota\BukuAnggotaController;
use App\Http\Controllers\Kepala\DashboardController as KepalaDashboardController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Kepala\PeminjamanController as KepalaPeminjamanController;
use App\Http\Controllers\Kepala\PetugasController;
use App\Http\Controllers\Petugas\AnggotaController;
use App\Http\Controllers\Petugas\BukuController;
use App\Http\Controllers\Petugas\PeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

// route halaman awal (welcome)
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH (LOGIN & REGISTER)
|--------------------------------------------------------------------------
*/

// menampilkan halaman login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');

// proses login
Route::post('/login', [LoginController::class, 'login']);

// menampilkan halaman register
Route::get('/register', [LoginController::class, 'showRegister']);

// proses register
Route::post('/register', [LoginController::class, 'register']);

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

// proses logout user
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')->with('success', 'Anda telah logout');
})->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE ANGGOTA
|--------------------------------------------------------------------------
*/

// semua route anggota menggunakan middleware auth dan role anggota
Route::middleware(['auth', 'role:anggota'])->group(function () {

    // dashboard & profile anggota
    Route::get('/anggota/dashboard', [AnggotaDashboardController::class, 'index']);
    Route::get('/anggota/profile', [AnggotaDashboardController::class, 'profile']);

    // halaman buku anggota
    Route::get('/anggota/buku', [BukuAnggotaController::class, 'index']);
    Route::get('/anggota/buku/detail/{id}', [BukuAnggotaController::class, 'detail']);

    // proses peminjaman
    Route::get('/anggota/pinjam/{id}', [BukuAnggotaController::class, 'formPinjam']);
    Route::post('/anggota/pinjam/store', [BukuAnggotaController::class, 'storePinjam']);

    // data peminjaman anggota
    Route::get('/anggota/peminjaman', [BukuAnggotaController::class, 'peminjamanSaya']);
    Route::get('/anggota/pengembalian/{id}', [BukuAnggotaController::class, 'ajukanPengembalian']);

    // riwayat peminjaman
    Route::get('/anggota/riwayat', [BukuAnggotaController::class, 'riwayat']);
    Route::get('/anggota/riwayat/detail/{id}', [BukuAnggotaController::class, 'detailPeminjaman']);
});


/*
|--------------------------------------------------------------------------
| ROUTE PETUGAS
|--------------------------------------------------------------------------
*/

// semua route petugas menggunakan middleware auth dan role petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {

    // dashboard & profile petugas
    Route::get('/petugas/dashboard', [PetugasDashboardController::class, 'index']);
    Route::get('/petugas/profile', [PetugasDashboardController::class, 'profile']);

    // CRUD buku
    Route::get('/petugas/buku', [BukuController::class, 'index']);
    Route::get('/petugas/buku/create', [BukuController::class, 'create']);
    Route::post('/petugas/buku/store', [BukuController::class, 'store']);
    Route::get('/petugas/buku/edit/{id}', [BukuController::class, 'edit']);
    Route::post('/petugas/buku/update/{id}', [BukuController::class, 'update']);
    Route::get('/petugas/buku/delete/{id}', [BukuController::class, 'delete']);
    Route::get('/petugas/buku/detail/{id}', [BukuController::class, 'detail']);

    // peminjaman
    Route::get('/petugas/peminjaman', [PeminjamanController::class, 'index']);
    Route::get('/petugas/peminjaman/create', [PeminjamanController::class, 'create']);
    Route::post('/petugas/peminjaman/store', [PeminjamanController::class, 'store']);
    Route::get('/petugas/peminjaman/konfirmasi/{id}', [PeminjamanController::class, 'konfirmasi']);
    Route::get('/petugas/peminjaman/tolak/{id}', [PeminjamanController::class, 'tolak']);
    Route::get('/petugas/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan']);
    Route::get('/petugas/peminjaman/tolak-pengembalian/{id}', [PeminjamanController::class, 'tolakPengembalian']);
    Route::get('/petugas/peminjaman/detail/{id}', [PeminjamanController::class, 'detail']);

    // halaman riwayat peminjaman
    Route::get(
        '/petugas/riwayat',
        [App\Http\Controllers\Petugas\PeminjamanController::class, 'riwayat']
    )->name('petugas.riwayat')->middleware('role:petugas');

    // detail riwayat
    Route::get(
        '/petugas/riwayat/detail/{id}',
        [PeminjamanController::class, 'detailRiwayat']
    )->name('petugas.riwayat.detail');

    // CRUD anggota
    Route::get('/petugas/anggota', [AnggotaController::class, 'index']);
    Route::get('/petugas/anggota/create', [AnggotaController::class, 'create']);
    Route::post('/petugas/anggota/store', [AnggotaController::class, 'store']);
    Route::get('/petugas/anggota/detail/{id}', [AnggotaController::class, 'detail']);
    Route::get('/petugas/anggota/edit/{id}', [AnggotaController::class, 'edit']);
    Route::post('/petugas/anggota/update/{id}', [AnggotaController::class, 'update']);
    Route::get('/petugas/anggota/delete/{id}', [AnggotaController::class, 'delete']);
});


/*
|--------------------------------------------------------------------------
| ROUTE KEPALA
|--------------------------------------------------------------------------
*/

// semua route kepala menggunakan middleware auth dan role kepala
Route::middleware(['auth', 'role:kepala'])->group(function () {

    // dashboard & profile kepala
    Route::get('/kepala/dashboard', [KepalaDashboardController::class, 'index']);
    Route::get('/kepala/profile', [KepalaDashboardController::class, 'profile']);

    // CRUD petugas
    Route::get('/kepala/petugas', [PetugasController::class, 'index']);
    Route::get('/kepala/petugas/create', [PetugasController::class, 'create']);
    Route::post('/kepala/petugas/store', [PetugasController::class, 'store']);
    Route::get('/kepala/petugas/detail/{id}', [PetugasController::class, 'detail']);
    Route::get('/kepala/petugas/edit/{id}', [PetugasController::class, 'edit']);
    Route::post('/kepala/petugas/update/{id}', [PetugasController::class, 'update']);
    Route::get('/kepala/petugas/delete/{id}', [PetugasController::class, 'delete']);
    Route::get('/kepala/petugas/nonaktif/{id}', [PetugasController::class, 'nonaktif']);
    Route::get('/kepala/petugas/aktif/{id}', [PetugasController::class, 'aktif']);

    // peminjaman & laporan
    Route::get('/kepala/peminjaman', [KepalaPeminjamanController::class, 'index']);
    Route::get('/kepala/peminjaman/detail/{id}', [KepalaPeminjamanController::class, 'detail']);
    Route::get('/kepala/laporan', [KepalaPeminjamanController::class, 'laporan']);
    Route::get('/kepala/laporan/pdf', [KepalaPeminjamanController::class, 'exportPdf']);
});


/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/

// jika route tidak ditemukan redirect ke login
Route::fallback(function () {
    return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
});

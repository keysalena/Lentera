<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
// Jangan lupa import controller lain jika ada, misalnya:
// use App\Http\Controllers\SiswaController; 

// --- GUEST ROUTES (Belum Login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});


// --- AUTHENTICATED ROUTES (Sudah Login) ---
Route::middleware('auth')->group(function () {

    // Tombol Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- DASHBOARD ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
        Route::post('/siswa/store', [AdminController::class, 'storeSiswa'])->name('siswa.store');
        Route::put('/siswa/{id}', [AdminController::class, 'updateSiswa'])->name('siswa.update');
        Route::delete('/siswa/{id}', [AdminController::class, 'destroySiswa'])->name('siswa.destroy');
        Route::get('/guru', [AdminController::class, 'guru'])->name('guru');
        Route::post('/guru/store', [AdminController::class, 'storeGuru'])->name('guru.store');
        Route::put('/guru/{id}', [AdminController::class, 'updateGuru'])->name('guru.update');
        Route::delete('/guru/{id}', [AdminController::class, 'destroyGuru'])->name('guru.destroy');
        Route::get('/sekolah', [AdminController::class, 'sekolah'])->name('sekolah');
        Route::post('/sekolah/store', [AdminController::class, 'storeSekolah'])->name('sekolah.store');
        Route::put('/sekolah/{id}', [AdminController::class, 'updateSekolah'])->name('sekolah.update');
        Route::delete('/sekolah/{id}', [AdminController::class, 'destroySekolah'])->name('sekolah.destroy');
        Route::get('/sekolah/{id}/detail', [AdminController::class, 'detailSekolah'])->name('sekolah.detail');
        // ── ROUTE KELOLA MAPEL ──
        Route::get('/mapel', [AdminController::class, 'mapel'])->name('mapel');
        Route::post('/mapel/store', [AdminController::class, 'storeMapel'])->name('mapel.store');
        Route::put('/mapel/{id}', [AdminController::class, 'updateMapel'])->name('mapel.update');
        Route::delete('/mapel/{id}', [AdminController::class, 'destroyMapel'])->name('mapel.destroy');
        // ── ROUTE KELOLA KEMAMPUAN ──
        Route::get('/kemampuan', [AdminController::class, 'kemampuan'])->name('kemampuan');
        Route::post('/kemampuan/store', [AdminController::class, 'storeKemampuan'])->name('kemampuan.store');
        Route::put('/kemampuan/{id}', [AdminController::class, 'updateKemampuan'])->name('kemampuan.update');
        Route::delete('/kemampuan/{id}', [AdminController::class, 'destroyKemampuan'])->name('kemampuan.destroy');
    });

    // --- DASHBOARD GURU ---
    Route::middleware('auth')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
        Route::get('/siswa', [GuruController::class, 'siswa'])->name('siswa');

        // Tambahkan ini untuk melihat detail siswa
        Route::get('/siswa/{id}', [GuruController::class, 'detailSiswa'])->name('siswa.detail');

        Route::get('/profil', [GuruController::class, 'profil'])->name('profil');
        Route::put('/profil', [GuruController::class, 'updateProfil'])->name('profil.update');
        // ── ROUTE BARU: Halaman Dominasi Bidang (Daftar Rekomendasi Bidang Siswa) ──
        Route::get('/dominasi-bidang', [GuruController::class, 'dominasiBidang'])->name('dominasi');
        // Route Ruang Konsultasi (Smart Triage)
        Route::get('/konsultasi', [GuruController::class, 'konsultasi'])->name('konsultasi');
        Route::put('/konsultasi/{id}/jadwal', [GuruController::class, 'jadwalkanKonsultasi'])->name('konsultasi.jadwal');
        Route::put('/konsultasi/{id}/selesai', [GuruController::class, 'selesaikanKonsultasi'])->name('konsultasi.selesai');
    });

    // --- DASHBOARD SISWA ---
    Route::middleware('auth')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/input', [SiswaController::class, 'input'])->name('input');
        Route::post('/input', [SiswaController::class, 'storeEksplorasi'])->name('eksplorasi.store');
        Route::get('/hasil', [SiswaController::class, 'hasil'])->name('hasil');
        Route::get('/konsultasi', [SiswaController::class, 'konsultasi'])->name('konsultasi');
        Route::post('/konsultasi', [SiswaController::class, 'storeKonsultasi'])->name('konsultasi.store');
        // Route untuk Profil Siswa
        Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');
        Route::put('/profil', [SiswaController::class, 'updateProfil'])->name('profil.update');
    });
});


// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return view('home');
});
Route::get('/tentang', function () {
    return view('about');
});

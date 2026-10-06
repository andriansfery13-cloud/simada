<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pegawai
    Route::get('pegawai/import/template', [PegawaiController::class, 'downloadTemplate'])->name('pegawai.import.template');
    Route::post('pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::resource('pegawai', PegawaiController::class);

    // Kegiatan
    Route::get('/kegiatan/kalender', [KegiatanController::class, 'kalender'])->name('kegiatan.kalender');
    Route::get('/api/calendar-events', [KegiatanController::class, 'calendarEvents'])->name('api.calendar-events');
    Route::get('/api/events-by-date', [KegiatanController::class, 'eventsByDate'])->name('api.events-by-date');
    Route::post('/kegiatan/{kegiatan}/kehadiran', [KegiatanController::class, 'updateKehadiran'])->name('kegiatan.kehadiran');
    Route::resource('kegiatan', KegiatanController::class);

    // Disposisi (hanya role tertentu yang bisa buat)
    Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
    Route::get('/disposisi/create', [DisposisiController::class, 'create'])->name('disposisi.create')
        ->middleware('role:admin,camat,umpeg');
    Route::post('/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store')
        ->middleware('role:admin,camat,umpeg');
    Route::get('/disposisi/{disposisi}', [DisposisiController::class, 'show'])->name('disposisi.show');
    Route::patch('/disposisi/{disposisi}/status', [DisposisiController::class, 'updateStatus'])->name('disposisi.update-status');

    // Rekapitulasi
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])->name('rekapitulasi.index');
    Route::get('/rekapitulasi/{pegawai}', [RekapitulasiController::class, 'detail'])->name('rekapitulasi.detail');

    // Dokumentasi
    Route::post('/kegiatan/{kegiatan}/dokumentasi', [DokumentasiController::class, 'store'])->name('dokumentasi.store');
    Route::delete('/dokumentasi/{dokumentasi}', [DokumentasiController::class, 'destroy'])->name('dokumentasi.destroy');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
});

require __DIR__.'/auth.php';

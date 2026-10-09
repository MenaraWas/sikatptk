<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKegiatanController;
use App\Http\Controllers\Admin\AdminPtkController;
use App\Http\Controllers\KegiatanSubmissionController;
use App\Http\Controllers\PublicRekapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Open Form SIKAT PTK MAN 2 Bantul)
|--------------------------------------------------------------------------
*/

// Open Form Upload Kegiatan (Max 10 per upload)
Route::get('/', [KegiatanSubmissionController::class, 'create'])->name('submission.create');
Route::post('/upload', [KegiatanSubmissionController::class, 'store'])->name('submission.store');
Route::get('/sukses/{code}', [KegiatanSubmissionController::class, 'success'])->name('submission.success');

// Search API untuk data guru/PTK
Route::get('/api/ptk/search', [KegiatanSubmissionController::class, 'searchPtk'])->name('api.ptk.search');

// Halaman Publik "Lihat Data SIKAT PTK"
Route::get('/data-kegiatan', [PublicRekapController::class, 'index'])->name('rekap.index');

/*
|--------------------------------------------------------------------------
| Admin Authentication & Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Kegiatan
        Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/kegiatan/export-csv', [AdminKegiatanController::class, 'exportCsv'])->name('kegiatan.export');
        Route::get('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'show'])->name('kegiatan.show');
        Route::post('/kegiatan/{kegiatan}/status', [AdminKegiatanController::class, 'updateStatus'])->name('kegiatan.status');
        Route::get('/kegiatan/{kegiatan}/download', [AdminKegiatanController::class, 'downloadFile'])->name('kegiatan.download');
        Route::delete('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('kegiatan.destroy');

        // Manajemen Master PTK
        Route::get('/ptk', [AdminPtkController::class, 'index'])->name('ptk.index');
        Route::post('/ptk', [AdminPtkController::class, 'store'])->name('ptk.store');
        Route::put('/ptk/{ptk}', [AdminPtkController::class, 'update'])->name('ptk.update');
        Route::delete('/ptk/{ptk}', [AdminPtkController::class, 'destroy'])->name('ptk.destroy');
        Route::post('/ptk/import-csv', [AdminPtkController::class, 'importCsv'])->name('ptk.import');
    });
});

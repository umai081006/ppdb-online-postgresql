<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/cek-status', [PublicController::class, 'cekStatus'])->name('cek-status');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'panitia') return redirect()->route('panitia.dashboard');
    return redirect()->route('siswa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PanitiaController as AdminPanitiaController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Manage Panitia
    Route::resource('panitia', AdminPanitiaController::class)->except(['show']);
    
    // Manage Announcements
    Route::resource('announcements', AdminAnnouncementController::class)->except(['show']);

    // Selection System
    Route::get('/selection', [App\Http\Controllers\Admin\SelectionController::class, 'index'])->name('selection.index');
    Route::post('/selection/run', [App\Http\Controllers\Admin\SelectionController::class, 'run'])->name('selection.run');
    Route::post('/selection/publish', [App\Http\Controllers\Admin\SelectionController::class, 'publish'])->name('selection.publish');
    Route::post('/selection/reset', [App\Http\Controllers\Admin\SelectionController::class, 'reset'])->name('selection.reset');
    Route::get('/selection/export-all', [App\Http\Controllers\Admin\SelectionController::class, 'exportAll'])->name('selection.export.all');
    Route::get('/selection/export/{jalurId}', [App\Http\Controllers\Admin\SelectionController::class, 'exportByJalur'])->name('selection.export.jalur');
});

use App\Http\Controllers\PanitiaController;

// Panitia Routes
Route::middleware(['auth', 'role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/dashboard', [PanitiaController::class, 'index'])->name('dashboard');
    Route::get('/registration/{id}', [PanitiaController::class, 'show'])->name('registration.show');
    Route::post('/document/{id}/verify', [PanitiaController::class, 'verifyDocument'])->name('document.verify');
});

use App\Http\Controllers\SiswaController;

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftaran', [SiswaController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [SiswaController::class, 'store'])->name('pendaftaran.store');
    Route::get('/dokumen', [SiswaController::class, 'uploadDokumenForm'])->name('dokumen.create');
    Route::post('/dokumen', [SiswaController::class, 'uploadDokumen'])->name('dokumen.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

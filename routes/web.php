<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiKaryawanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiAdminController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\GenerateQRController;
use App\Http\Controllers\AdminController;   


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/scanqr', function () {
    return view('scanqr');
})->middleware('auth')->name('scanqr');

// Halaman acara

Route::get('/acara', [AcaraController::class, 'index'])->name('acara');
Route::get('/api/schedules', [AcaraController::class, 'getSchedules'])->name('acara.schedules');
Route::get('/tambah_acara', [AcaraController::class, 'showAddEventForm'])->name('acara.add');
Route::post('/save_event', [AcaraController::class, 'storeEvent'])->name('acara.store');
Route::get('/detail_acara/{id}', [AcaraController::class, 'showEventDetail'])->name('acara.detail');
Route::get('/edit/{id}', [AcaraController::class, 'editEvent'])->name('acara.edit');
Route::put('/acara/update/{id}', [AcaraController::class, 'updateEvent'])->name('acara.update');
Route::delete('/delete/{id}', [AcaraController::class, 'deleteEvent'])->name('acara.delete');
Route::get('/acara/{id_tamu}', [AcaraController::class, 'showEventDetail'])->name('event.show');
Route::get('download/qr/{id_tamu}/{kode_acara}', [AcaraController::class, 'downloadQrCode'])->name('download.qr');
Route::post('/acara/{id}/store-guest', [AcaraController::class, 'addGuestToEvent'])->name('acara.store.guest');
Route::delete('/acara/{eventId}/remove-guest/{guestId}', [AcaraController::class, 'removeGuestFromEvent'])->name('acara.remove.guest');
Route::get('/acara/{id}/download-all-qr', [AcaraController::class, 'downloadAllQrCodes'])->name('acara.download.all.qr');
Route::get('/acara/{id}/tambah-tamu', [AcaraController::class, 'showAddGuestForm'])->name('acara.showAddGuestForm');
Route::post('/acara/{id}/add-guest', [AcaraController::class, 'addGuestToEvent'])->name('acara.addGuestToEvent');
Route::get('/acara/guest/{id_tamu}', [AcaraController::class, 'getGuest'])->name('acara.getGuest');
Route::post('/acara/{id}/store-new-guest', [AcaraController::class, 'storeNewGuest'])->name('acara.store.new.guest');
Route::get('/tamu/generate-id', [TamuController::class, 'generateId'])->name('tamu.generateId');
Route::get('/get-last-kode-acara', [AcaraController::class, 'getLastKodeAcara']);
Route::get('/kunjungan/download-pdf', [PresensiAdminController::class, 'downloadKunjunganPdf'])->name('kunjungan.download.pdf');
Route::get('/acara/{kode_acara}', [PresensiAdminController::class, 'getEventDetails'])->name('acara.details');

Route::get('/kunjungan', [PresensiAdminController::class, 'showKunjungan'])->name('kunjungan');
Route::get('/download-laporan', [PresensiAdminController::class, 'downloadLaporan'])->name('download.laporan');
// Halaman admin dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Rute autentikasi
Auth::routes();

// Group untuk yang sudah login (middleware auth)
Route::middleware(['auth'])->group(function () {
    // Dashboard untuk karyawan
    Route::get('/karyawan', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
    
    // Profil karyawan
    Route::get('/karyawan/profile', [KaryawanController::class, 'profile'])->name('karyawan.profile');
    
    // Presensi Karyawan
    Route::get('/karyawan/presensi', [PresensiKaryawanController::class, 'index'])->name('karyawan.presensi.index');
    Route::post('/karyawan/presensi/store', [PresensiKaryawanController::class, 'store'])->name('karyawan.presensi.store');
    
    // Data karyawan
    Route::get('/karyawan/datakar', [KaryawanController::class, 'datakar'])->name('karyawan.datakar');
    
    // QR Code untuk karyawan
    Route::get('/karyawan/{nip}/qrcode', [KaryawanController::class, 'showQrCode'])->name('karyawan.qrcode');
    
    // Edit dan Hapus karyawan
    Route::get('/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('karyawan/{nip}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    Route::resource('karyawan', KaryawanController::class);


    // Menambahkan karyawan baru
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');

    // Home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Validasi
    Route::post('/validasi', [ValidasiController::class, 'validasi'])->name('validasi');
});



// Rute tamu (auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/tamu', [TamuController::class, 'datatamu'])->name('tamu.datatamu');
    Route::get('/tamu/create', [TamuController::class, 'create'])->name('tamu.create');
    Route::post('/tamu', [TamuController::class, 'store'])->name('tamu.store');
    Route::get('/tamu/{id}/edit', [TamuController::class, 'edit'])->name('tamu.edit');
    Route::put('/tamu/{id}', [TamuController::class, 'update'])->name('tamu.update');
    Route::delete('/tamu/{id}', [TamuController::class, 'destroy'])->name('tamu.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Route::resource('/admin/karyawan', KaryawanController::class)->except(['index'])->names('admin.karyawan');
    // Route::get('/admin/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan.index');

    Route::resource('/presensi', PresensiAdminController::class)->except(['index'])->names('admin.presensi');
    Route::get('/presensi', [PresensiAdminController::class, 'index'])->name('admin.presensi');

    Route::resource('/presensis', PresensiAdminController::class)->except(['index'])->names('admin.presensis');
    Route::get('/presensis', [PresensiAdminController::class, 'index'])->name('admin.presensis');

    Route::get('/presensi/{kode_acara}', [PresensiAdminController::class, 'getPregetPresensiAndKunjunganByAcara']);
    Route::get('/event/{kode_acara}', [PresensiAdminController::class, 'getPresensiAndKunjunganByAcara']);

    // Menghasilkan QR Code
    Route::get('/admin/generateqr', [GenerateQRController::class, 'admin.generate']);
    Route::resource('/generateqr', GenerateQRController::class)->except(['generate'])->names('admin.generateqr');
    Route::get('/generateqr', [GenerateQRController::class, 'generate'])->name('admin.generateqr');
    Route::get('/generateqr', [GenerateQRController::class, 'showForm'])->name('generate.form');
    Route::post('/generateqr', [GenerateQRController::class, 'generate'])->name('generate.qr');

});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin dashboard route
    Route::get('/admin/datauser', [AdminController::class, 'datauser'])->name('admin.datauser');
    // Route untuk mengelola admin
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    
});

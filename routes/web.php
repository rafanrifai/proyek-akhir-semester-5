<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BmnController;
use App\Http\Controllers\SensusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RiwayatKerusakanController;
use App\Http\Controllers\KlasifikasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/active-unit', [DashboardController::class, 'switchUnit'])->name('dashboard.active-unit');

    // Admin Routes
    Route::middleware('role:ADMIN')->group(function () {
        Route::resource('units', UnitController::class);
        Route::resource('users', UserController::class);
    });

    // Modul 3: Data BMN & Modul 4: Data Kondisi Barang
    Route::resource('bmn', BmnController::class);

    // Sensus Fisik Route
    Route::get('sensus', [SensusController::class, 'index'])->name('sensus.index');
    Route::post('sensus/search', [SensusController::class, 'search'])->name('sensus.search');
    Route::get('sensus/{bmn}', [SensusController::class, 'show'])->name('sensus.show');
    Route::post('sensus/{bmn}/verify', [SensusController::class, 'verify'])->name('sensus.verify');

    // Modul 5: Riwayat Kerusakan
    Route::get('riwayat-kerusakan', [RiwayatKerusakanController::class, 'index'])->name('riwayat-kerusakan.index');
    Route::get('riwayat-kerusakan/create', [RiwayatKerusakanController::class, 'create'])->name('riwayat-kerusakan.create');
    Route::post('riwayat-kerusakan', [RiwayatKerusakanController::class, 'store'])->name('riwayat-kerusakan.store');

    // Modul 6: Klasifikasi ML & Modul 7: Hasil & Riwayat Klasifikasi
    Route::get('klasifikasi', [KlasifikasiController::class, 'index'])->name('klasifikasi.index');
    Route::get('klasifikasi/create', [KlasifikasiController::class, 'create'])->name('klasifikasi.create');
    Route::post('klasifikasi', [KlasifikasiController::class, 'store'])->name('klasifikasi.store');
    Route::get('klasifikasi/tree', [KlasifikasiController::class, 'tree'])->name('klasifikasi.tree');
    Route::get('klasifikasi/{klasifikasi}', [KlasifikasiController::class, 'show'])->name('klasifikasi.show');
    Route::delete('klasifikasi/{klasifikasi}', [KlasifikasiController::class, 'destroy'])->name('klasifikasi.destroy');

    // Modul 8: Laporan BMN (Data Barang & Hasil Klasifikasi)
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('report/cetak-bmn', [ReportController::class, 'cetakBmn'])->name('report.cetak-bmn');
    Route::get('report/export-bmn', [ReportController::class, 'exportBmnCsv'])->name('report.export-bmn');
    Route::get('report/cetak-klasifikasi', [ReportController::class, 'cetakKlasifikasi'])->name('report.cetak-klasifikasi');
    Route::get('report/export-klasifikasi', [ReportController::class, 'exportKlasifikasiCsv'])->name('report.export-klasifikasi');
    Route::get('report/ba', [ReportController::class, 'cetakBA'])->name('report.ba');
    Route::post('report/archives', [ReportController::class, 'storeArchive'])->name('report.archives.store');
    Route::get('report/archives', [ReportController::class, 'archives'])->name('report.archives');
    Route::delete('report/archives/{archive}', [ReportController::class, 'destroyArchive'])->name('report.archives.destroy');
    Route::get('report/archives/{archive}', [ReportController::class, 'showArchive'])->name('report.archives.show');
});

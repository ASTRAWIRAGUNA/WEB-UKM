<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AnggotaUkmController;
use App\Http\Controllers\KegiatanUkmController;
use App\Http\Controllers\UkmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardUkmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KasUkmController;
use App\Http\Controllers\LaporanUkmController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');



// Admin route
Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
    Route::resource('/dashboard-admin', DashboardAdminController::class);
    Route::post('/dashboard-admin/toggle-all-registration-status', [DashboardAdminController::class, 'toggleAllRegistrationStatus'])->name('dashboard-admin.toggleAllRegistrationStatus');
    Route::post('/dashboard-admin/min-kegiatan', [DashboardAdminController::class, 'minKegiatan'])->name('dashboard-admin.min-kegiatan');
    // Route::resource('/manage-user', UserController::class);
    // Route::post('/import-user', [UserController::class, 'importUser'])->name('import-user');
    Route::resource('/manage-ukm', UkmController::class);
    Route::resource('/manage-laporan-ukm', LaporanUkmController::class);
    Route::resource('/log-activity', LogActivityController::class);
    Route::resource('/manage-user', UserController::class);
    Route::post('/import-user', [UserController::class, 'importUser'])->name('import-user');
});

// Route untuk BPH_UKM
Route::middleware(['auth', RoleMiddleware::class . ':BPH_UKM'])->group(function () {
    Route::resource('/dashboard-ukm', DashboardUkmController::class);
    Route::put('update/{ukm_id}', [DashboardUkmController::class, 'update'])->name('update.profile');
    Route::resource('/manage-anggota', controller: AnggotaUkmController::class);
    Route::get('export-anggota', [AnggotaUkmController::class, 'exportAnggota'])->name('eksport-anggota');
    Route::resource('/manage-kegiatan-ukm', KegiatanUkmController::class);
    Route::get('/manage-kas-ukm', [KasUkmController::class, 'index'])->name('manage-kas-ukm.index');
    Route::post('/setKas', [KasUkmController::class, 'setKas'])->name('setKas');
    Route::post('/pay-kas/{user_id}', [KasUkmController::class, 'payKas'])->name('pay-kas');
    Route::get('/eksport-kas', [KasUkmController::class, 'exportKas'])->name('eksport-kas');
});

// Route untuk Mahasiswa
Route::middleware(['auth', RoleMiddleware::class . ':Mahasiswa'])->group(function () {
    Route::resource('/home', HomeController::class);
    Route::post('/ukm/join', [AnggotaUkmController::class, 'joinUkm'])->name('ukm.join');
    Route::get('/detail/{ukm_id}', [HomeController::class, 'detail'])->name('home.detail');
    Route::post('/detail/scan', [HomeController::class, 'scan'])->name('attendance.scan');
});

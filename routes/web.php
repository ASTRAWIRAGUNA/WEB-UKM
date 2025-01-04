<?php

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

Route::resource('/manage-user', UserController::class);
// Route::post('/import-users', [UserController::class, 'importExcel'])->name('import.users');


Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
    Route::resource('/dashboard-admin', DashboardAdminController::class);
    Route::post('/dashboard-admin/toggle-all-registration-status', [DashboardAdminController::class, 'toggleAllRegistrationStatus'])->name('dashboard-admin.toggleAllRegistrationStatus');
    // Route::resource('/manage-user', UserController::class);
    Route::resource('/manage-ukm', UkmController::class);
    Route::resource('/manage-laporan-ukm', LaporanUkmController::class);
    Route::resource('/log-activity', LogActivityController::class);
});

// Route untuk BPH_UKM
Route::middleware(['auth', RoleMiddleware::class . ':BPH_UKM'])->group(function () {
    Route::resource('/dashboard-ukm', DashboardUkmController::class);
    Route::resource('/manage-anggota', AnggotaUkmController::class);
    Route::resource('/manage-kegiatan-ukm', KegiatanUkmController::class);
    Route::resource('/manage-kas-ukm', KasUkmController::class);
});

// Route untuk Mahasiswa
Route::middleware(['auth', RoleMiddleware::class . ':Mahasiswa'])->group(function () {
    Route::resource('/home', HomeController::class);
    Route::get('/detail', [HomeController::class, 'detail']);
});

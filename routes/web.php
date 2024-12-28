<?php

use App\Http\Controllers\UkmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardUkmController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

Route::resource('/manage-user', UserController::class);

Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
    Route::resource('/dashboard-admin', DashboardAdminController::class);
    // Route::resource('/manage-user', UserController::class);
    Route::resource('/manage-ukm', UkmController::class);
});

// Route untuk BPH_UKM
Route::middleware(['auth', RoleMiddleware::class . ':BPH_UKM'])->group(function () {
    Route::resource('/dashboard-ukm', DashboardUkmController::class);
});

// Route untuk Mahasiswa
Route::middleware(['auth', RoleMiddleware::class . ':Mahasiswa'])->group(function () {
    Route::resource('/home', HomeController::class);
});

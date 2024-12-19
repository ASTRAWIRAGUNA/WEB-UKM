<?php

use App\Http\Controllers\UkmController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard-admin', function () {
    return view('admin.dashboardAdmin');
});

Route::resource('/manage-user', UserController::class);
Route::resource('/manage-ukm', UkmController::class);

Route::get('/dashboard-bph', function () {
    return view('bph.dashboardBPH');
});

Route::get('/home', function () {
    return view('mahasiswa.home');
});

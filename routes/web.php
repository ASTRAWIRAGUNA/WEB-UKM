<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard-admin', function () {
    return view('admin.dashboardAdmin');
});

Route::get('/manage-user', function () {
    return view('admin.manageUser');
});

Route::get('/dashboard-bph', function () {
    return view('bph.dashboardBPH');
});

Route::get('/home', function () {
    return view('mahasiswa.home');
});

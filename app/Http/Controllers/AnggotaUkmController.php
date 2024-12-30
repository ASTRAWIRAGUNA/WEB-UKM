<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnggotaUkmController extends Controller
{
    public function index()
    {
        return view('bph.manageAnggota');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KegiatanUkmController extends Controller
{
    public function index()
    {
        return view('bph.manageKegiatan');
    }
}

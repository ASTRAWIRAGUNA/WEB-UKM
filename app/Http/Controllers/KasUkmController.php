<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasUkmController extends Controller
{
    public function index()
    {
        return view('bph.manageKas');
    }
}

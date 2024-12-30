<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $ukms = Ukm::where('registration_status', 'active')->get();

        // Mengirimkan data ke view
        return view('mahasiswa.home', compact('ukms'));
    }
}

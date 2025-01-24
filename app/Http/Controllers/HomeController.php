<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        $ukms = Ukm::all();

        $ukmFollowed = $user->ukm->pluck('ukm_id')->toArray(); // UKM yang diikuti oleh pengguna

        return view('mahasiswa.home', compact('ukms', 'ukmFollowed'));
    }


    public function detail($ukm_id)
    {
        $activities = Activity::where('ukm_id', $ukm_id)->get();

        // Kirim data kegiatan ke view
        return view('mahasiswa.detail', compact('activities'));
    }
}

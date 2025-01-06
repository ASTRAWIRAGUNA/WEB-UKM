<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaUkmController extends Controller
{
    public function index()
    {
        return view('bph.manageAnggota');
    }

    public function joinUkm(Request $request)
    {
        $user_id = Auth::user()->user_id;

        // $request->validate([
        //     'ukm' => 'required|exists:ukms,ukm_id',
        //     'user' => 'required|exists:users,user_id',
        // ]);

        $ukmId = $request->ukm;

        // Cari user
        $mahasiswa = User::where('user_id', $user_id)->first();

        // Debugging
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Cari UKM
        $ukm = Ukm::where('ukm_id', $ukmId)->first();

        if (!$ukm) {
            return redirect()->back()->with('error', 'UKM tidak ditemukan.');
        }

        // Cek apakah sudah terdaftar
        if ($mahasiswa->ukm()->where('ukm_user.ukm_id', $ukmId)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di UKM ini.');
        }


        // Tambahkan ke tabel pivot
        $mahasiswa->ukm()->attach($ukm);

        return redirect()->route('home.index')->with('success', 'Berhasil mendaftar UKM.');
    }
}

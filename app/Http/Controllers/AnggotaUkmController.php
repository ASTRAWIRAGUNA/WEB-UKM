<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnggotaUkmController extends Controller
{
    public function index()
    {
        $current_user = Auth::user();

        $ukm = $current_user->bphUkm;

        $members = $ukm->members;

        $users = User::where('role', 'Mahasiswa')
            ->leftJoin('ukm_user', 'users.user_id', '=', 'ukm_user.user_id')
            ->whereNull('ukm_user.ukm_id')
            ->get();


        return view('bph.manageAnggota', compact('members', 'users'));
    }

    public function store(Request $request)
    {
        $current_user = Auth::user();

        $ukm = $current_user->bphUkm;

        if (!$ukm) {
            return redirect()->back()->withErrors(['error' => 'UKM tidak ditemukan.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Pengguna tidak ditemukan.']);
        }

        if ($ukm->members->contains($user)) {
            return redirect()->back()->withErrors(['error' => 'Pengguna sudah tergabung dalam UKM ini.']);
        }

        $ukm->members()->attach($user->user_id);

        return redirect()->route('manage-anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, $user_id)
    {

        $request->validate([
            'email' => 'required|email',
        ]);

        $newUser = User::where('email', $request->email)->first();

        if ($newUser) {
            DB::table('ukm_user')
                ->where('user_id', $user_id)
                ->update(['user_id' => $newUser->user_id]);

            return redirect()->route('manage-anggota.index')->with('success', 'User ID pada pivot berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Pengguna dengan email tersebut tidak ditemukan!');
        }
    }


    public function destroy($ukm_user_id)
    {
        $member = User::findOrFail($ukm_user_id);

        $member->ukm()->detach();

        return redirect()->route('manage-anggota.index')->with('success', 'Anggota berhasil dihapus!');
    }



    public function joinUkm(Request $request)
    {
        $user_id = Auth::user()->user_id;

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

<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;

class UkmController extends Controller
{
    public function index(Request $request)
    {
        $ukms = Ukm::all();

        // Mengambil user dengan role BPH_UKM yang belum terhubung ke UKM mana pun
        $bph_ukm_users = User::where('role', 'BPH_UKM')
            ->whereDoesntHave('bphUkms') // Menggunakan relasi 'bphUkms'
            ->get();

        return view('admin.manageUkm', compact('ukms', 'bph_ukm_users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'name_ukm' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'role_ketua' => 'required|string|max:255',
            'role_wakil' => 'required|string|max:255',
            'role_bendahara' => 'required|string|max:255',
            'role_sekertaris' => 'required|string|max:255',
        ]);

        $profile_photo = $request->file('profile_photo');
        $imageName = $profile_photo->hashName();
        $profile_photo->storeAs('public/profile_photo', $imageName);

        Ukm::created([
            'profile_photo' => $imageName,
            'name_ukm' => $request->name_ukm,
            'description' => $request->description,
            'role_ketua' => $request->role_ketua,
            'role_wakil' => $request->role_wakil,
            'role_bendahara' => $request->role_bendahara,
            'role_sekertaris' => $request->role_sekertaris
        ]);

        return redirect()->route('manage-ukm.index')->with('success', 'UKM Created Successfully');
    }

    public function update(Request $request, $ukm_id)
    {

        $ukm = Ukm::findOrFail($ukm_id);
    }

    public function destroy($ukm_id) {}
}

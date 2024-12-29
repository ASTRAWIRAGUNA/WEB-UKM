<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardUkmController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ukm = $user->bphUkm;
        return view('bph.dashboardBPH', compact('ukm'));
    }

    public function update(Request $request, $manage_ukm)
    {

        $user = Auth::user();

        // Pastikan user yang login hanya bisa mengupdate UKM yang terkait dengan mereka
        $ukm = Ukm::where('ukm_id', $manage_ukm)->where('bph_id', $user->user_id)->first();

        $request->validate([
            'profile_photo_ukm' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'name_ukm' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Update foto profile UKM jika ada
        if ($request->hasFile('profile_photo_ukm')) {
            if ($ukm->profile_photo_ukm && Storage::exists('public/profile_photo_ukm/' . $ukm->profile_photo_ukm)) {
                Storage::delete('public/profile_photo_ukm/' . $ukm->profile_photo_ukm);
            }

            $profile_photo_ukm = $request->file('profile_photo_ukm');
            $imageName = $profile_photo_ukm->hashName();
            $profile_photo_ukm->storeAs('public/profile_photo_ukm', $imageName);

            $ukm->profile_photo_ukm = $imageName;
        }

        // Update nama UKM dan deskripsi
        $ukm->name_ukm = $request->name_ukm;
        $ukm->description = $request->description;

        // Simpan perubahan
        $ukm->save();
        return redirect()->route('dashboard-ukm.index')->with('success', 'Profile UKM updated successfully.');
    }
}

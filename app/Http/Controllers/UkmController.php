<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UkmController extends Controller
{
    public function index(Request $request)
    {
        $ukms = Ukm::with('bph')->get();
        $bph_ukm_users = User::where('role', 'BPH_UKM')
            ->whereNotIn('id', Ukm::pluck('bph_id')) // Hindari pengguna yang sudah digunakan
            ->get();

        return view('admin.manageUkm', compact('ukms', 'bph_ukm_users'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'profile_photo_ukm' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'name_ukm' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bph_id' => 'required|exists:users,user_id', // Pastikan BPH terdaftar
        ]);

        $profile_photo_ukm = $request->file('profile_photo_ukm');
        $imageName = $profile_photo_ukm->hashName();
        $profile_photo_ukm->storeAs('public/profile_photo_ukm', $imageName);

        Ukm::create([
            'profile_photo_ukm' => $imageName,
            'name_ukm' => $request->name_ukm,
            'description' => $request->description,
            'bph_id' => $request->bph_id, // Mengaitkan BPH dengan UKM
        ]);

        return redirect()->route('manage-ukm.index')->with('success', 'UKM Created Successfully');
    }

    public function update(Request $request, $ukm_id)
    {
        $ukm = Ukm::findOrFail($ukm_id);

        $request->validate([
            'profile_photo_ukm' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'name_ukm' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bph_id' => 'required|exists:users,user_id',
        ]);

        if ($request->hasFile('profile_photo_ukm')) {
            if ($ukm->profile_photo_ukm && Storage::exists('public/profile_photo_ukm/' . $ukm->profile_photo_ukm)) {
                Storage::delete('public/profile_photo_ukm/' . $ukm->profile_photo_ukm);
            }

            $profile_photo_ukm = $request->file('profile_photo_ukm');
            $imageName = $profile_photo_ukm->hashName();
            $profile_photo_ukm->storeAs('public/profile_photo_ukm', $imageName);

            $ukm->profile_photo_ukm = $imageName;
        }

        $ukm->name_ukm = $request->name_ukm;
        $ukm->description = $request->description;
        $ukm->bph_id = $request->bph_id;

        $ukm->save();

        return redirect()->route('manage-ukm.index')->with('success', 'UKM Updated Successfully');
    }


    public function destroy($ukm_id)
    {
        $ukm = Ukm::findOrFail($ukm_id);

        if ($ukm->profile_photo_ukm && Storage::exists('public/profile_photo_ukm/' . $ukm->profile_photo_ukm)) {
            Storage::delete('public/profile_photo_ukm/' . $ukm->profile_photo_ukm);
        }

        $ukm->delete();

        return redirect()->route('manage-ukm.index')->with('success', 'UKM berhasil dihapus');
    }
}

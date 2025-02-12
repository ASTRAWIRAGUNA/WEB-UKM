<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;

class UkmController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mencari data UKM berdasarkan nama UKM, deskripsi, atau email BPH
        $ukms = Ukm::with('bph')
            ->when($search, function ($query, $search) {
                $query->where('name_ukm', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('bph', function ($q) use ($search) {
                        $q->where('email', 'like', "%$search%");
                    });
            })
            ->paginate(20); // Pagination dengan maksimal 20 data per halaman

        // Ambil data BPH UKM yang belum memiliki UKM
        $bph_ukm_users = User::where('role', 'BPH_UKM')
            ->whereNotIn('user_id', Ukm::pluck('bph_id'))
            ->get();

        return view('admin.manageUkm', compact('ukms', 'bph_ukm_users', 'search'));
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

        $ukm_exists = Ukm::where('name_ukm', $request->name_ukm)->exists();
        $email_exists = User::where('email', $request->email)->exists();

        if ($ukm_exists) {
            return redirect()->route('manage-ukm.index')->with('error', 'UKM telah dibuat sebelumnya');
        }

        if ($email_exists) {
            return redirect()->route('manage-ukm.index')->with('error', 'Email yang ada masukkan sudah terdaftar');
        }

        Ukm::create([
            'profile_photo_ukm' => $imageName,
            'name_ukm' => $request->name_ukm,
            'description' => $request->description,
            'bph_id' => $request->bph_id, // Mengaitkan BPH dengan UKM
            'registration_status' => 'deactivated',
        ]);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user menambah ukm baru $request->imageName, $request->name_ukm, $request->description, $$request->bph_id",
        ]);

        $logs->save();

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
            'registration_status' => 'in:active,deactivated',
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

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mengupdate ukm baru $ukm",
        ]);

        $logs->save();


        $ukm->save();

        return redirect()->route('manage-ukm.index')->with('success', 'UKM Updated Successfully');
    }


    public function destroy($ukm_id)
    {
        $ukm = Ukm::findOrFail($ukm_id);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mengupdate ukm baru $ukm",
        ]);

        $logs->save();

        if ($ukm->profile_photo_ukm && Storage::exists('public/profile_photo_ukm/' . $ukm->profile_photo_ukm)) {
            Storage::delete('public/profile_photo_ukm/' . $ukm->profile_photo_ukm);
        }



        $ukm->delete();

        return redirect()->route('manage-ukm.index')->with('success', 'UKM berhasil dihapus');
    }
}

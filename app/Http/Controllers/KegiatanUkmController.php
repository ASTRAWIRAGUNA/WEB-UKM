<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Activity;

class KegiatanUkmController extends Controller
{
    public function index()
    {
        $current_user = Auth::user();

        $ukm = $current_user->bphUkm;

        $kegiatans = Activity::where('ukm_id', $ukm->ukm_id)->get();

        return view('bph.manageKegiatan', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_activity' => 'required|string|max:255',
            'date' => 'required|date',
            'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->proof_photo) {

            $proof_photo = $request->file('proof_photo');
            $imageName = $proof_photo->hashName(); // Generate unique name
            $proof_photo->storeAs('public/proof_photo', $imageName); // Save file in storage
        }

        // Create a new UKM record
        $activity = Activity::create([
            'ukm_id' => Auth::user()->bphUkm->ukm_id,
            'name_activity' => $request->name_activity,
            'date' => $request->date,
            'status_activity' => 'Pending',

        ]);

        $activity->save();

        return redirect()->route('manage-kegiatan-ukm.index')->with('success', 'Berhasil Membuat Kegiatan UKM.');
    }


    public function update(Request $request, $activities_id)
    {
        $request->validate([
            'name_activity' => 'required|string|max:255',
            'date' => 'required|date',
            'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kegiatan = Activity::findOrFail($activities_id);

        if ($request->hasFile('proof_photo')) {
            // Hapus gambar lama jika ada
            if ($kegiatan->proof_photo && Storage::exists('public/proof_photo/' . $kegiatan->proof_photo)) {
                Storage::delete('public/proof_photo/' . $kegiatan->proof_photo);
            }

            // Simpan gambar baru
            $proof_photo = $request->file('proof_photo');
            $imageName = $proof_photo->hashName();
            $proof_photo->storeAs('public/proof_photo', $imageName);

            $kegiatan->proof_photo = $imageName; // Update nama gambar di database
        }

        // Update data UKM lainnya
        $kegiatan->update([
            'name_activity' => $request->name_activity,
            'date' => $request->date,
            'proof_photo' => $kegiatan->proof_photo, // Tetap simpan gambar yang sudah ada
            'status_activity' => 'Pending',
        ]);

        return redirect()->route('manage-kegiatan-ukm.index')->with('success', 'UKM berhasil diperbarui!');
    }

    public function destroy($activities_id)
    {
        $kegiatan = Activity::findOrFail($activities_id);

        if ($kegiatan->proof_photo && file_exists(storage_path('app/public/proof_photo/' . $kegiatan->proof_photo))) {
            unlink(storage_path('app/public/proof_photo/' . $kegiatan->proof_photo));
        }

        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus!');
    }
}

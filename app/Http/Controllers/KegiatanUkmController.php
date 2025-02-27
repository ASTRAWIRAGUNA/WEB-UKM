<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Activity;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Logs;

class KegiatanUkmController extends Controller
{
    public function index(Request $request)
    {
        $current_user = Auth::user();
        $ukm = $current_user->bphUkm;

        // Ambil parameter search dari request
        $search = $request->input('search');

        // Query untuk mencari data kegiatan berdasarkan nama kegiatan, tanggal, atau pesan
        $kegiatans = Activity::where('ukm_id', $ukm->ukm_id)
            ->when($search, function ($query, $search) {
                return $query->where('name_activity', 'like', "%$search%")
                    ->orWhere('date', 'like', "%$search%")
                    ->orWhere('message', 'like', "%$search%");
            })
            ->paginate(20); // Pagination dengan maksimal 10 data per halaman

        return view('bph.manageKegiatan', compact('kegiatans', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_activity' => 'required|string|max:255',
            'date' => 'required|date',
            'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('proof_photo')) {
            $proof_photo = $request->file('proof_photo');
            $imageName = $proof_photo->hashName();
            $proof_photo->storeAs('public/proof_photo', $imageName);
        }

        // Simpan data kegiatan ke database terlebih dahulu
        $activity = Activity::create([
            'ukm_id' => Auth::user()->bphUkm->ukm_id,
            'name_activity' => $request->name_activity,
            'date' => $request->date,
            'proof_photo' => $imageName,
            'status_activity' => 'Pending',
        ]);

        // Buat QR Code setelah mendapatkan ID dari database
        $qr_codeData = 'UKM-' . Auth::user()->bphUkm->ukm_id . '-ACT-' . $activity->activities_id;
        $qr_codePath = 'public/qr_code/' . uniqid() . '.png';
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qr_codeData);

        // Unduh QR Code dari API
        $qrImageContent = file_get_contents($qrCodeUrl);
        Storage::put($qr_codePath, $qrImageContent);

        // Perbarui aktivitas dengan path QR Code
        $activity->update([
            'qr_code' => str_replace('public/', '', $qr_codePath),
        ]);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user membuat kegiatan baru $request->name_activity",
        ]);

        $logs->save();

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
            if ($kegiatan->proof_photo && Storage::exists('public/proof_photo/' . $kegiatan->proof_photo)) {
                Storage::delete('public/proof_photo/' . $kegiatan->proof_photo);
            }

            $proof_photo = $request->file('proof_photo');
            $imageName = $proof_photo->hashName();
            $proof_photo->storeAs('public/proof_photo', $imageName);
            $kegiatan->proof_photo = $imageName;
        }

        // Hapus QR Code lama jika ada
        if ($kegiatan->qr_code && Storage::exists('public/' . $kegiatan->qr_code)) {
            Storage::delete('public/' . $kegiatan->qr_code);
        }

        // Buat QR Code baru dengan ID aktivitas
        $qr_codeData = 'UKM-' . $kegiatan->ukm_id . '-ACT-' . $kegiatan->activities_id;
        $qr_codePath = 'public/qr_code/' . uniqid() . '.png';
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qr_codeData);

        // Unduh QR Code dari API
        $qrImageContent = file_get_contents($qrCodeUrl);
        Storage::put($qr_codePath, $qrImageContent);

        // Perbarui data kegiatan
        $kegiatan->update([
            'name_activity' => $request->name_activity,
            'date' => $request->date,
            'proof_photo' => $kegiatan->proof_photo,
            'qr_code' => str_replace('public/', '', $qr_codePath),
            'status_activity' => 'Pending',
        ]);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mengupdate kegiatan baru $request->name_activity",
        ]);

        $logs->save();

        return redirect()->route('manage-kegiatan-ukm.index')->with('success', 'UKM berhasil diperbarui!');
    }


    public function destroy($activities_id)
    {
        $kegiatan = Activity::findOrFail($activities_id);

        // Hapus semua entri terkait di tabel 'attendances'
        $kegiatan->attendances()->delete();

        // Menghapus foto proof_photo jika ada
        if ($kegiatan->proof_photo && Storage::exists('public/proof_photo/' . $kegiatan->proof_photo)) {
            Storage::delete('public/proof_photo/' . $kegiatan->proof_photo);
        }

        // Menghapus QR Code jika ada
        if ($kegiatan->qr_code && Storage::exists('public/' . $kegiatan->qr_code)) {
            Storage::delete('public/' . $kegiatan->qr_code);
        }

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user menghapus kegiatan $kegiatan->name_activity",
        ]);

        $logs->save();

        // Menghapus record kegiatan
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus!');
    }
}

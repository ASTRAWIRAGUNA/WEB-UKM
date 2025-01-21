<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Activity;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        $imageName = null;
        if ($request->hasFile('proof_photo')) {
            $proof_photo = $request->file('proof_photo');
            $imageName = $proof_photo->hashName();
            $proof_photo->storeAs('public/proof_photo', $imageName);
        }

        // Data untuk QR Code
        $qr_codeData = 'UKM-' . Auth::user()->bphUkm->ukm_id . '-' . now()->timestamp;
        $qr_codePath = 'public/qr_code/' . uniqid() . '.png';

        // Generate QR Code menggunakan API eksternal
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qr_codeData);

        // Unduh QR Code dari API ke folder storage
        $qrImageContent = file_get_contents($qrCodeUrl);
        Storage::put($qr_codePath, $qrImageContent);

        // Simpan data kegiatan ke database
        Activity::create([
            'ukm_id' => Auth::user()->bphUkm->ukm_id,
            'name_activity' => $request->name_activity,
            'date' => $request->date,
            'proof_photo' => $imageName,
            'qr_code' => str_replace('public/', '', $qr_codePath), // Hapus 'public/' untuk akses publik
            'status_activity' => 'Pending',
        ]);

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

        // Mengupdate foto proof_photo jika ada file baru
        if ($request->hasFile('proof_photo')) {
            // Menghapus foto lama jika ada
            if ($kegiatan->proof_photo && Storage::exists('public/proof_photo/' . $kegiatan->proof_photo)) {
                Storage::delete('public/proof_photo/' . $kegiatan->proof_photo);
            }

            // Menyimpan foto baru
            $proof_photo = $request->file('proof_photo');
            $imageName = $proof_photo->hashName();
            $proof_photo->storeAs('public/proof_photo', $imageName);
            $kegiatan->proof_photo = $imageName;
        }

        // Hapus QR Code lama jika ada
        if ($kegiatan->qr_code && Storage::exists('public/' . $kegiatan->qr_code)) {
            Storage::delete('public/' . $kegiatan->qr_code);
        }

        // Generate QR Code baru
        $qr_codeData = 'UKM-' . $kegiatan->ukm_id . '-' . now()->timestamp;
        $qr_codePath = 'public/qr_code/' . uniqid() . '.png';

        // Generate QR Code menggunakan API eksternal
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qr_codeData);

        // Unduh QR Code dari API ke folder storage
        $qrImageContent = file_get_contents($qrCodeUrl);
        Storage::put($qr_codePath, $qrImageContent);

        // Mengupdate Activity
        $kegiatan->update([
            'name_activity' => $request->name_activity,
            'date' => $request->date,
            'proof_photo' => $kegiatan->proof_photo, // Nama gambar yang baru
            'qr_code' => str_replace('public/', '', $qr_codePath), // Path qr_code relatif yang baru
            'status_activity' => 'Pending',
        ]);

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

        // Menghapus record kegiatan
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus!');
    }
}

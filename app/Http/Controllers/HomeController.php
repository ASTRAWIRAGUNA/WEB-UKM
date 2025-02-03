<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\Attendances;
use Illuminate\Support\Facades\Log;

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

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'ukm_id' => 'required|integer',
            'activities_id' => 'required|integer',
            'qr_code' => 'required|string', // Pastikan QR Code ada
        ]);

        $user = Auth::user();
        $activityId = $request->activities_id;
        $qrCodeData = trim($request->qr_code); // Hilangkan spasi yang tidak perlu

        // Pecah QR Code format "UKM-1-ACT-8"
        $parts = explode('-', $qrCodeData);

        // Pastikan format benar sebelum lanjut
        if (count($parts) !== 4 || $parts[0] !== "UKM" || $parts[2] !== "ACT") {
            return response()->json([
                'message' => 'QR Code tidak valid!',
                'invalid_qr' => true
            ]);
        }

        $parsedActivityId = (int) $parts[3]; // Ambil activities_id dari QR Code

        // Validasi apakah activity_id dari QR Code sesuai dengan request
        if ($parsedActivityId !== (int) $activityId) {
            return response()->json([
                'message' => 'QR Code tidak sesuai dengan aktivitas!',
                'invalid_qr' => true
            ]);
        }

        // Cek apakah user sudah absen
        $attendance = Attendances::where('user_id', $user->user_id)
            ->where('activities_id', $activityId)
            ->first();

        if ($attendance) {
            return response()->json([
                'message' => 'Anda sudah absen!',
                'already_absent' => true
            ]);
        }

        // Simpan absen
        Attendances::create([
            'activities_id' => $activityId,
            'user_id' => $user->user_id,
            'is_present' => true,
        ]);

        return response()->json([
            'message' => 'Absen berhasil!',
            'already_absent' => false
        ]);
    }
}

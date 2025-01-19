<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class LaporanUkmController extends Controller
{
    public function index()
    {
        $laporan_kegiatans = Activity::all();

        return view('admin.manageLaporan', compact('laporan_kegiatans'));
    }

    public function update(Request $request, $activities_id)
    {
        // Validasi status yang diterima
        $request->validate([
            'status_activity' => 'required|in:Pending,Diterima,Ditolak',
            'message' => 'required|string|MAX:255',
        ]);

        // Temukan kegiatan berdasarkan ID
        $kegiatan = Activity::find($activities_id);

        // Perbarui status kegiatan
        $kegiatan->message = $request->message;
        $kegiatan->status_activity = $request->status_activity;
        $kegiatan->save();

        return redirect()->route('manage-laporan-ukm.index')->with('success', 'Status kegiatan berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\Logs;

class LaporanUkmController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mencari data laporan kegiatan berdasarkan nama kegiatan, nama UKM, atau pesan
        $laporan_kegiatans = Activity::with('ukm')
            ->when($search, function ($query, $search) {
                $query->where('name_activity', 'like', "%$search%")
                    ->orWhereHas('ukm', function ($q) use ($search) {
                        $q->where('name_ukm', 'like', "%$search%");
                    })
                    ->orWhere('message', 'like', "%$search%");
            })
            ->paginate(20); // Pagination dengan maksimal 10 data per halaman

        return view('admin.manageLaporan', compact('laporan_kegiatans', 'search'));
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

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user memberikan status $request->status_activity pada kegiatan $kegiatan->name_activity",
        ]);

        $logs->save();

        return redirect()->route('manage-laporan-ukm.index')->with('success', 'Status kegiatan berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Kas;
use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasUkmController extends Controller
{
    public function index(Request $request)
    {
        $current_user = Auth::user();
        $ukm_id = $current_user->bphUkm->ukm_id;

        $kas = Kas::where('ukm_id', $ukm_id)->first();
        $amountCash = $kas ? $kas->cash : 0;

        $search = $request->input('search');
        $dateActivities = $current_user->bphUkm->activities()
            ->when($search, function ($query, $search) {
                return $query->where('name_activity', 'like', "%$search%")
                    ->orWhere('date', 'like', "%$search%");
            })
            ->paginate(10);

        $ukm = Ukm::find($ukm_id);
        $members = $ukm->members;
        $totalKas = Kas::where('is_payment', true)->sum('amount');
        return view('bph.manageKas', compact('dateActivities', 'amountCash', 'search', 'members', 'totalKas'));
    }

    public function setKas(Request $request)
    {

        $current_user = Auth::user();
        $ukm_id = $current_user->bphUkm->ukm_id ?? null;

        if (!$ukm_id) {
            return response()->json(['error' => 'User tidak memiliki UKM'], 400);
        }

        // Validasi input
        $request->validate([
            'cash' => 'required|numeric',
        ]);

        // Update atau create kas
        $kas = Ukm::updateOrCreate(
            ['ukm_id' => $ukm_id],
            ['cash' => $request->cash]
        );

        // Mengembalikan response JSON
        return response()->json([
            'cash' => $kas->cash,
            'message' => 'Kas berhasil diperbarui'
        ]);
    }


    public function payKas(Request $request, $user_id)
    {
        $current_user = Auth::user();
        $ukm_id = $current_user->bphUkm->ukm_id;

        // Validasi input
        $request->validate([
            'date' => 'required|date',
        ]);

        // Ambil data kegiatan berdasarkan tanggal
        $activity = Activity::where('ukm_id', $ukm_id)
            ->where('date', $request->date)
            ->first();

        if (!$activity) {
            return redirect()->back()->with('error', 'Kegiatan tidak ditemukan!');
        }

        $ukm = Ukm::find($ukm_id);
        $amount = $ukm ? $ukm->cash : 0;

        // Simpan pembayaran kas
        $kas = Kas::create([
            'ukm_id' => $ukm_id,
            'user_id' => $user_id,
            'activities_id' => $activity->activities_id,
            'date' => $request->date,
            'amount' => $amount,
            'is_payment' => true,
        ]);

        $kas->save();

        return redirect()->back()->with('success', 'Pembayaran kas berhasil disimpan!');
    }
}

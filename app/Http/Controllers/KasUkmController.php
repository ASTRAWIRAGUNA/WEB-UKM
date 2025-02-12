<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasUkmController extends Controller
{
    public function index()
    {
        $current_user = Auth::user();
        $ukm_id = $current_user->bphUkm->ukm_id;
        $kas = Kas::where('ukm_id', $ukm_id)->first();

        $amountCash = $kas ? $kas->cash : 0;

        $dateActivities = $current_user->bphUkm->activities()->get();

        return view('bph.manageKas', compact('dateActivities', 'amountCash'));
    }

    public function setKas(Request $request)
    {
        $current_user = Auth::user();
        $ukm_id = $current_user->bphUkm->ukm_id;

        // Validasi input
        $request->validate([
            'cash' => 'required|numeric',
        ]);

        // Update atau create kas
        $kas = Kas::updateOrCreate(
            ['ukm_id' => $ukm_id],
            ['cash' => $request->cash]
        );

        // Mengembalikan response JSON
        return response()->json([
            'message' => 'Kas berhasil diupdate!',
            'amountCash' => $kas->cash, // Kirim nilai cash yang baru
        ]);
    }
}

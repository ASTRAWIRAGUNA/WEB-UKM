<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Kas;
use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function exportKas(Request $request)
    {
        $current_user = Auth::user();
        $ukm_id = $current_user->bphUkm->ukm_id;

        // Ambil data kas dengan relasi user dan kegiatan
        $kas = Kas::where('ukm_id', $ukm_id)
            ->where('is_payment', true)
            ->with(['user', 'activity']) // Pastikan relasi sudah didefinisikan
            ->get();

        $activities = Activity::where('ukm_id', $ukm_id)->orderBy('date', 'asc')->get();

        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header tabel (sesuai dengan tampilan di web)
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Email');

        $colIndex = 'C'; // Mulai dari kolom C untuk kegiatan
        foreach ($activities as $activity) {
            $sheet->setCellValue($colIndex . '1', $activity->date);
            $colIndex++;
        }

        $sheet->setCellValue($colIndex . '1', 'Jumlah');

        // Isi data (per anggota UKM)
        $row = 2; // Mulai dari baris kedua
        $no = 1;
        $users = User::whereHas('kas', function ($query) use ($ukm_id) {
            $query->where('ukm_id', $ukm_id);
        })->get();

        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $user->email);

            $colIndex = 'C';
            foreach ($activities as $activity) {
                $kasEntry = $kas->where('user_id', $user->user_id)->where('activities_id', $activity->activities_id)->first();
                $status = $kasEntry ? '✔' : '✘';
                $sheet->setCellValue($colIndex . $row, $status);
                $colIndex++;
            }
            $totalAmount = $kas->where('user_id', $user->id)->sum('amount');
            $sheet->setCellValue($colIndex . $row, "Rp. " . number_format($totalAmount, 0, ',', '.'));

            $row++;
        }

        // Simpan file sementara
        $filename = 'kas-ukm-' . $current_user->bphUkm->name_ukm . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempFilePath = storage_path('app/' . $filename);
        $writer->save($tempFilePath);

        // Download file
        return response()->download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }
}

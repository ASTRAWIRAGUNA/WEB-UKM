<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Attendances;
use App\Models\Kas;
use App\Models\Ukm;
use App\Models\User;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnggotaUkmController extends Controller
{
    public function index(Request $request)
    {
        $current_user = Auth::user();
        $ukm = $current_user->bphUkm;

        $search = $request->input('search');

        // Query untuk mencari data anggota berdasarkan nama atau email
        $members = $ukm->members()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%$search%")
                        ->orWhere('nim', 'like', "%$search%");
                });
            })
            ->paginate(20); // Pagination dengan maksimal 10 data per halaman

        // Hitung presentase kehadiran dan pembayaran kas untuk setiap anggota
        foreach ($members as $member) {
            // Hitung total kegiatan yang harus dihadiri user ini
            $totalActivities = Activity::where('ukm_id', $ukm->ukm_id)->count();

            // Hitung total kehadiran user ini
            $totalAttendance = Attendances::where('user_id', $member->user_id)
                ->where('is_present', true)
                ->count();

            // Hitung persentase kehadiran
            $attendancePercentage = ($totalActivities > 0) ? round(($totalAttendance / $totalActivities) * 100, 2) : 0;

            // Hitung total kas yang harus dibayar oleh user ini
            $totalKas = Kas::where('ukm_id', $ukm->ukm_id)
                ->where('user_id', $member->user_id) // Pastikan kas dihitung per user
                ->count();

            // Hitung total kas yang sudah dibayar oleh user ini
            $totalPaidKas = Kas::where('user_id', $member->user_id)
                ->where('ukm_id', $ukm->ukm_id) // Pastikan kas dihitung per UKM
                ->where('is_payment', true)
                ->count();

            // Hitung persentase kas
            $kasPercentage = ($totalKas > 0) ? round(($totalPaidKas / $totalKas) * 100, 2) : 0;

            $isActive = ($kasPercentage == 100 && $attendancePercentage >= 75);

            $member->attendancePercentage = $attendancePercentage;
            $member->kasPercentage = $kasPercentage;
            $member->isActive = $isActive;
        }

        // Query untuk mencari user yang belum menjadi anggota UKM
        $users = User::where('role', 'Mahasiswa')
            ->leftJoin('ukm_user', 'users.user_id', '=', 'ukm_user.user_id')
            ->whereNull('ukm_user.ukm_id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%$search%")
                        ->orWhere('users.email', 'like', "%$search%");
                });
            })
            ->paginate(20); // Pagination dengan maksimal 20 data per halaman

        return view('bph.manageAnggota', compact('members', 'users', 'search'));
    }

    public function store(Request $request)
    {
        $current_user = Auth::user();

        $ukm = $current_user->bphUkm;

        if (!$ukm) {
            return redirect()->back()->withErrors(['error' => 'UKM tidak ditemukan.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Pengguna tidak ditemukan.']);
        }

        if ($ukm->members->contains($user)) {
            return redirect()->back()->withErrors(['error' => 'Pengguna sudah tergabung dalam UKM ini.']);
        }

        $ukm->members()->attach($user->user_id);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user menambahkan anggota $user->email ke UKM $ukm->name_ukm",
        ]);

        $logs->save();

        return redirect()->route('manage-anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, $user_id)
    {

        $request->validate([
            'email' => 'required|email',
        ]);

        $newUser = User::where('email', $request->email)->first();

        if ($newUser) {
            DB::table('ukm_user')
                ->where('user_id', $user_id)
                ->update(['user_id' => $newUser->user_id]);
            $currrent_user = Auth::user()->email;
            $logs = Logs::create([
                'user_id' => Auth::id(),
                'activity' => "$currrent_user melakukan update anggota dengan email $request->email",
            ]);

            $logs->save();

            return redirect()->route('manage-anggota.index')->with('success', 'User ID pada pivot berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Pengguna dengan email tersebut tidak ditemukan!');
        }
    }


    public function destroy($ukm_user_id)
    {
        $member = User::findOrFail($ukm_user_id);

        $member->ukm()->detach();

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user menghapus anggota $member->email dari UKM $member->name_ukm",
        ]);

        $logs->save();

        return redirect()->route('manage-anggota.index')->with('success', 'Anggota berhasil dihapus!');
    }



    public function joinUkm(Request $request)
    {
        $user_id = Auth::user()->user_id;

        $ukmId = $request->ukm;

        // Cari user
        $mahasiswa = User::where('user_id', $user_id)->first();

        // Debugging
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Cari UKM
        $ukm = Ukm::where('ukm_id', $ukmId)->first();

        if (!$ukm) {
            return redirect()->back()->with('error', 'UKM tidak ditemukan.');
        }

        // Cek apakah sudah terdaftar
        if ($mahasiswa->ukm()->where('ukm_user.ukm_id', $ukmId)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di UKM ini.');
        }


        // Tambahkan ke tabel pivot
        $mahasiswa->ukm()->attach($ukm);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mendaftar UKM $ukm->name_ukm",
        ]);

        $logs->save();

        return redirect()->route('home.index')->with('success', 'Berhasil mendaftar UKM.');
    }

    public function exportAnggota(Request $request)
    {
        $current_user = Auth::user();
        $ukm = $current_user->bphUkm;

        // Ambil data anggota UKM
        $members = $ukm->members;

        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header tabel
        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Kehadiran');
        $sheet->setCellValue('D1', 'Kas');
        $sheet->setCellValue('E1', 'Status Aktif UKM'); // Kolom baru untuk status aktif

        // Isi data
        $row = 2; // Mulai dari baris kedua
        foreach ($members as $member) {
            // Hitung presentase kehadiran dan kas
            $totalActivities = Activity::where('ukm_id', $ukm->ukm_id)->count();
            $totalAttendance = Attendances::where('user_id', $member->user_id)
                ->where('is_present', true)
                ->count();
            $attendancePercentage = ($totalActivities > 0) ? round(($totalAttendance / $totalActivities) * 100, 2) : 0;

            $totalKas = Kas::where('ukm_id', $ukm->ukm_id)
                ->where('user_id', $member->user_id)
                ->count();
            $totalPaidKas = Kas::where('user_id', $member->user_id)
                ->where('ukm_id', $ukm->ukm_id)
                ->where('is_payment', true)
                ->count();
            $kasPercentage = ($totalKas > 0) ? round(($totalPaidKas / $totalKas) * 100, 2) : 0;

            // Tentukan status aktif
            $isActive = ($kasPercentage == 100 && $attendancePercentage >= 75);
            $statusAktif = $isActive ? 'Aktif' : 'Tidak Aktif';

            // Isi data ke spreadsheet
            $sheet->setCellValue('A' . $row, $member->nim);
            $sheet->setCellValue('B' . $row, $member->email);
            $sheet->setCellValue('C' . $row, $attendancePercentage . '%');
            $sheet->setCellValue('D' . $row, $kasPercentage . '%');
            $sheet->setCellValue('E' . $row, $statusAktif); // Kolom status aktif
            $row++;
        }

        // Simpan file sementara
        $filename = 'anggota_' . $ukm->name_ukm . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempFilePath = storage_path('app/' . $filename);
        $writer->save($tempFilePath);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user melakukan export anggota UKM $ukm->name_ukm",
        ]);

        $logs->save();

        // Download file
        return response()->download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }
}

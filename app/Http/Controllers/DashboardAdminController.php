<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ukm;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;

class DashboardAdminController extends Controller
{
    public function index()
    {

        $ukms = Ukm::all();
        $c_users = User::count();
        $c_ukms = Ukm::count();
        $c_activities = Activity::count();
        $c_mhs = User::where('role', 'Mahasiswa')->count();

        $min_activity = Ukm::query()->min('min_activity');

        // Periksa apakah semua UKM memiliki status registration_status 'active'
        $isActive = !$ukms->contains(fn($ukm) => $ukm->registration_status == 'deactivated');


        return view('admin.dashboardAdmin', compact('ukms', 'isActive', 'c_users', 'c_ukms', 'c_activities', 'c_mhs', 'min_activity'));
    }


    public function toggleAllRegistrationStatus(Request $request)
    {
        $status = $request->input('status') == 'on' ? 'active' : 'deactivated';

        Ukm::query()->update(['registration_status' => $status]);

        $currrent_user = Auth::user()->email;

        if ($status == 'active') {
            $logs = Logs::create([
                'user_id' => Auth::id(),
                'activity' => "$currrent_user mengaktifkan status registrasi semua UKM",
            ]);
            $logs->save();
        } else {
            $logs = Logs::create([
                'user_id' => Auth::id(),
                'activity' => "$currrent_user menonaktifkan status registrasi semua UKM",
            ]);
            $logs->save();
        }

        return response()->json([
            'message' => 'Status UKM telah diperbarui!',
            'status' => $status
        ]);
    }

    public function minKegiatan(Request $request)
    {
        // Validasi input
        $request->validate([
            'min_activity' => 'required|integer|min:1',
        ]);

        // Update semua UKM dengan nilai minimal kegiatan yang baru
        Ukm::query()->update(['min_activity' => $request->input('min_activity')]);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user men-set minimal kegiatan sebesar $request->min_activity",
        ]);

        $logs->save();

        return response()->json([

            'min_activity' => $request->input('min_activity')
        ]);
    }
}

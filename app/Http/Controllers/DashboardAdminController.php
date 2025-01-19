<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ukm;

class DashboardAdminController extends Controller
{
    public function index()
    {
        
        $ukms = Ukm::all();
        $c_users = User::count();
        $c_ukms = Ukm::count();
        $c_activities = Activity::count();
        $c_mhs = User::where('role', 'Mahasiswa')->count();

        // Periksa apakah semua UKM memiliki status registration_status 'active'
        $isActive = !$ukms->contains(fn($ukm) => $ukm->registration_status == 'deactivated');


        return view('admin.dashboardAdmin', compact('ukms', 'isActive', 'c_users', 'c_ukms', 'c_activities', 'c_mhs'));
    }


    public function toggleAllRegistrationStatus(Request $request)
    {
        $status = $request->input('status') == 'on' ? 'active' : 'deactivated';

        Ukm::query()->update(['registration_status' => $status]);


        return response()->json([
            'message' => 'Status UKM telah diperbarui!',
            'status' => $status
        ]);
    }
}

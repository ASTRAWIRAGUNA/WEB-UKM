<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ukm;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Mengambil semua UKM
        $ukms = Ukm::all();

        // Periksa apakah semua UKM memiliki status registration_status 'active'
        $isActive = !$ukms->contains(fn($ukm) => $ukm->registration_status == 'deactivated');


        return view('admin.dashboardAdmin', compact('ukms', 'isActive'));
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

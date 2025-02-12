<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mencari data log activity berdasarkan activity atau email user
        $logs = Logs::with('user')
            ->when($search, function ($query, $search) {
                $query->where('activity', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%$search%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20); // Pagination dengan maksimal 10 data per halaman

        return view('admin.logActivity', compact('logs', 'search'));
    }
}

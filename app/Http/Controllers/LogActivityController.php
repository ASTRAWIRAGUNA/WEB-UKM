<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index()
    {
        $logs = Logs::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.logActivity', compact('logs'));
    }
}

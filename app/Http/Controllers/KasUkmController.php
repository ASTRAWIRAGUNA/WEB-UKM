<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasUkmController extends Controller
{
    public function index()
    {
        $current_user = Auth::user();
        $dateActivities = $current_user->bphUkm->activities()->get();
        return view('bph.manageKas', compact('dateActivities'));
    }
}

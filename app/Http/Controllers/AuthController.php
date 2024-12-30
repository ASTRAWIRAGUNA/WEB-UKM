<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            switch ($user->role) {
                case 'Admin':
                    $currrent_user = Auth::user()->email;
                    $logs = Logs::create([
                        'user_id' => Auth::id(),
                        'activity' => "$currrent_user melakukan login",
                    ]);

                    $logs->save();
                    return redirect()->route('dashboard-admin.index');
                case 'BPH_UKM':
                    $currrent_user = Auth::user()->email;
                    $logs = Logs::create([
                        'user_id' => Auth::id(),
                        'activity' => "$currrent_user melakukan login",
                    ]);

                    $logs->save();
                    return redirect()->route('dashboard-ukm.index');
                case 'Mahasiswa':
                    $currrent_user = Auth::user()->email;
                    $logs = Logs::create([
                        'user_id' => Auth::id(),
                        'activity' => "$currrent_user melakukan login",
                    ]);

                    $logs->save();
                    return redirect()->route('home.index');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors('Role tidak dikenal.');
            }
        }

        return redirect()->route('login')->withErrors('Email atau password salah.');
    }

    public function logout()
    {
        Auth::logout();
        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user melakukan login",
        ]);

        $logs->save();
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        return view('admin.manageUser', compact('users'));
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nim' => 'required|string|max:255|unique:users,nim',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Admin,BPH_UKM,Mahasiswa',
        ]);



        // Simpan data user ke database
        $user = User::create([
            'nim' => $request->nim,
            'email' => $request->email,
            'text_password' => $request->password,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mengupdate user baru $user",
        ]);

        $logs->save();

        $user->save();

        return redirect()->route('manage-user.index')->with('success', 'User Created Successfully');
    }

    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $user->nim = $request->input('nim');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        if ($request->input('text_password')) {
            $user->text_password = $request->input('text_password');
            $user->password = Hash::make($request->input('text_password'));
        }

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mengupdate user baru $user",
        ]);

        $logs->save();

        $user->save();

        return redirect()->route('manage-user.index')->with('success', 'User updated successfully');
    }

    public function destroy($user_id)
    {
        $userToDelete = User::find($user_id);

        $loggedInUser = Auth::user();

        if (!$userToDelete) {
            return redirect()->route('master-user.index')->with('alert', 'Pengguna tidak ditemukan.');
        }

        if ($loggedInUser && $loggedInUser->user_id === $userToDelete->user_id) {
            return redirect()->route('master-user.index')->with('alert', 'anda tidak dapat menghapus akun Anda sendiri.');
        }

        $currrent_user = Auth::user()->email;
        $logs = Logs::create([
            'user_id' => Auth::id(),
            'activity' => "$currrent_user mengupdate ukm baru $userToDelete",
        ]);

        $logs->save();

        $userToDelete->delete();

        return redirect()->route('manage-user.index')->with('success', 'User deleted successfully');
    }
}

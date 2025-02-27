<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use PhpOffice\PhpSpreadsheet\IOFactory;


class UserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter search dari request
        $search = $request->input('search');

        // Query untuk mencari data user berdasarkan NIM, email, atau nama
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('nim', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('role', 'like', "%$search%");
            })
            ->paginate(20); // Pagination dengan maksimal 20 data per halaman

        return view('admin.manageUser', compact('users', 'search'));
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
            'activity' => "$currrent_user menambah user baru $user",
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
            'activity' => "$currrent_user mengupdate user baru $userToDelete",
        ]);

        $logs->save();

        $userToDelete->delete();

        return redirect()->route('manage-user.index')->with('success', 'User deleted successfully');
    }

    public function importUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            foreach ($rows as $key => $row) {
                if ($key == 0) {
                    // Skip header row
                    continue;
                }

                User::create([
                    'nim' => $row[0],
                    'email' => $row[1],
                    'text_password' => $row[2],
                    'password' => Hash::make($row[2]),
                    'role' => $row[3],
                ]);

                $currrent_user = Auth::user()->email;
                $logs = Logs::create([
                    'user_id' => Auth::id(),
                    'activity' => "$currrent_user menambahkan user baru",
                ]);

                $logs->save();
            }

            return redirect()->back()->with('success', 'Users imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing users: ' . $e->getMessage());
        }
    }
}

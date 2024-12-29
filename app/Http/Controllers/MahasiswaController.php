<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class MahasiswaController extends Controller
{
    
    public function manageAnggota()
    {
       
        $anggota = User::all(); 
        
        
        return view('mahasiswa.manage_anggota', compact('anggota'));
    }
}

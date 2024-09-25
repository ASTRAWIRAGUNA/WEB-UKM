<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukm extends Model
{
    protected $fillable = ['name', 'description', 'profile_photo'];

    // Relasi ke anggota (mahasiswa)
    public function members()
    {
        return $this->belongsToMany(User::class, 'ukm_user')->withTimestamps();
    }

    // Relasi ke BPH UKM
    public function bphs()
    {
        return $this->belongsToMany(User::class, 'bph_ukm_ukm', 'ukm_id', 'bph_ukm_id')->withTimestamps();
    }

    // Relasi ke kegiatan
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    // Relasi ke laporan (Report)
    public function reports()
    {
        return $this->hasMany(Reports::class);
    }

    // Relasi ke kas UKM
    public function kas()
    {
        return $this->hasMany(Kas::class);
    }

    // Relasi ke absensi kegiatan UKM
    public function absences()
    {
        return $this->hasManyThrough(Attendances::class, Activity::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukm extends Model
{

    protected $primaryKey = 'ukm_id';
    protected $fillable = ['name_ukm', 'description', 'profile_photo_ukm', 'bph_id', 'registration_status', 'min_activity', 'cash'];

    // Relasi ke anggota (mahasiswa)a
    public function members()
    {
        return $this->belongsToMany(User::class, 'ukm_user', 'ukm_id', 'user_id')->withTimestamps();
    }


    // Relasi ke BPH UKM
    public function bph()
    {
        return $this->belongsTo(User::class, 'bph_id');
    }


    // Relasi ke kegiatan
    public function activities()
    {
        return $this->hasMany(Activity::class, 'ukm_id', 'ukm_id'); // 'ukm_id' adalah foreign key di tabel activities
    }

    // Relasi ke kas UKM
    public function kas()
    {
        return $this->hasMany(Kas::class, 'ukm_id', 'ukm_id');
    }

    // Relasi ke absensi kegiatan UKM
    public function absences()
    {
        return $this->hasManyThrough(Attendances::class, Activity::class)->onDelete('cascade');
    }
}

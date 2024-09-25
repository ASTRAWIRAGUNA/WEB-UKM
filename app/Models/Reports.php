<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $fillable = ['ukm_id', 'bph_ukm_id', 'title', 'description', 'proof_photo', 'status'];

    // Relasi ke UKM
    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }

    // Relasi ke BPH UKM (User yang membuat laporan)
    public function bphUkm()
    {
        return $this->belongsTo(User::class, 'bph_ukm_id');
    }
}

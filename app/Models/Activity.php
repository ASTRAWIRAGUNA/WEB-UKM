<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'date', 'ukm_id', 'proof_photo'];

    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendances::class);
    }
}

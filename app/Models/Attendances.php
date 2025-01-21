<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    protected $primaryKey = 'attendances_id';  // Pastikan primary key menggunakan 'attendances_id'

    protected $fillable = ['activities_id', 'user_id', 'is_present', 'status_active']; // Ganti 'activity_id' menjadi 'activities_id'

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activities_id');  // Pastikan nama kolom 'activities_id'
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');  // Pastikan kolom 'user_id'
    }

    protected static function booted()
    {
        static::deleting(function ($attendance) {
            // Pastikan ketika activity dihapus, relasi user_id di-set null
            if ($attendance->activity) {
                $attendance->user_id = null;
                $attendance->save();
            }
        });
    }
}

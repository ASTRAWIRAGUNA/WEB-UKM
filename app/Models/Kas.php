<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $primaryKey = 'kas_id';
    protected $fillable = ['ukm_id', 'user_id', 'activities_id', 'amount', 'is_payment'];

    public function ukm()
    {
        return $this->belongsTo(Ukm::class, 'ukm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activities_id');
    }
}

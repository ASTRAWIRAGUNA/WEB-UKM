<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $primaryKey = 'kas_id';
    protected $fillable = ['ukm_id', 'user_id', 'amount'];

    public function ukm()
    {
        return $this->belongsTo(Ukm::class, 'ukm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

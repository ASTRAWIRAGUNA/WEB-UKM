<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $primaryKey = 'user_id';
    protected $fillable = ['nim', 'email', 'password', 'text_password', 'role'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relasi ke Ukm Sebagai BphUKM
    public function bphUkm()
    {
        return $this->hasOne(Ukm::class, 'bph_id');
    }


    // Relasi ke UKM (sebagai anggota)
    public function ukm()
    {
        return $this->belongsToMany(Ukm::class, 'ukm_user', 'user_id', 'ukm_id')->withTimestamps();
    }

    // Relasi ke log aktivitas
    public function logs()
    {
        return $this->hasMany(Logs::class);
    }

    public function kas()
    {
        return $this->hasMany(Kas::class, 'user_id');
    }
}

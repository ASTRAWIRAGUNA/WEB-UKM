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
    protected $fillable = ['nim', 'email', 'text_password', 'password', 'role_id'];


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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function bphUkms()
    {
        return $this->belongsToMany(Ukm::class, 'bph_ukm_ukm', 'bph_ukm_id', 'ukm_id')->withTimestamps();
    }

    // Relasi ke UKM (sebagai anggota)
    public function ukms()
    {
        return $this->belongsToMany(Ukm::class, 'ukm_user')->withTimestamps();
    }

    // Relasi ke laporan yang dikirim oleh BPH UKM
    public function reports()
    {
        return $this->hasMany(Reports::class, 'bph_ukm_id');
    }

    // Relasi ke log aktivitas
    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
}

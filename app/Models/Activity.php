<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 *
 * @property $id
 * @property $ukm_id
 * @property $name
 * @property $date
 * @property $proof_photo
 * @property $created_at
 * @property $updated_at
 *
 * @property Ukm $ukm
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Activity extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'activities';

    protected $primaryKey = 'activities_id';
    protected $fillable = ['ukm_id', 'name_activity', 'date', 'proof_photo', 'status_activity', 'message'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ukm()
    {
        return $this->belongsTo(Ukm::class, 'ukm_id', 'ukm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

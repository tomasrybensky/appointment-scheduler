<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = ['name'];

    /**
     * Get the all opening hours for therapist
     */
    public function openingHours()
    {
        return $this->hasMany(OpeningHours::class);
    }

    /**
     * Get the all therapist's appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    const DEFAULT_DURATION = 40;
    const DURATION_OPTIONS = [25, 40];

    protected $fillable = ['start', 'end', 'therapist_id'];

    protected $dates = ['start', 'end'];
}

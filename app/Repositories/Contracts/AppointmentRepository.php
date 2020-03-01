<?php

namespace App\Repositories\Contracts;

use App\Models\Therapist;
use Carbon\Carbon;
use Prettus\Repository\Contracts\RepositoryInterface;

interface AppointmentRepository extends RepositoryInterface
{
    /**
     * Get available appointments
     *
     * @param int $duration
     * @param Carbon $date
     * @param Therapist $therapist
     *
     * @return mixed
     */
    public function getAvailableAppointments(int $duration, Carbon $date, Therapist $therapist);
}
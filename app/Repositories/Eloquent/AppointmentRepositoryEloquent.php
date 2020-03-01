<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Models\Therapist;
use App\Repositories\Contracts\AppointmentRepository;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;

class AppointmentRepositoryEloquent extends BaseRepository implements AppointmentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Appointment::class;
    }

    /**
     * Get available appointments
     *
     * @param int $duration
     * @param Carbon $date
     * @param Therapist $therapist
     *
     * @return mixed
     */
    public function getAvailableAppointments(int $duration, Carbon $date, Therapist $therapist)
    {
        $slots = $this->convertOpeningHoursToSlots($therapist, $date);
        $slots = $this->splitSlotsByAppointments($slots, $therapist, $date);

        $result = [];

        foreach ($slots as $slot) {
            $possibleAppointmentsInSlot = $this->addPossibleAppointmentsFromSlot($slot, $duration);

            if (!empty($possibleAppointmentsInSlot)) {
                foreach (array_values($possibleAppointmentsInSlot) as $appointment) {
                    $result[] = $appointment;
                }
            }
        }

        $result = collect($result);
        $sorted = $result->sortBy('start');

        return $sorted;
    }

    /**
     * Convert opening hours to slots array
     *
     * @param Therapist $therapist
     * @param Carbon $date
     *
     * @return array
     */
    protected function convertOpeningHoursToSlots(Therapist $therapist, Carbon $date)
    {
        $slots = [];

        foreach ($therapist->openingHours as $openingHours) {
            $slots[] = [
                'start' => Carbon::create($date->format('d.m.Y') . $openingHours->open),
                'end' => Carbon::create($date->format('d.m.Y') . $openingHours->close)
            ];
        }

        return $slots;
    }

    /**
     * Split available time slots by existing appointments
     *
     * @param array $slots
     * @param Therapist $therapist
     * @param Carbon $date
     *
     * @return array
     */
    protected function splitSlotsByAppointments(array $slots, Therapist $therapist, Carbon $date)
    {
        $start = Carbon::createFromTimestamp($date->timestamp)->startOfDay();
        $end = Carbon::createFromTimestamp($date->timestamp)->endOfDay();

        $appointments = $therapist->appointments()
            ->where('start', '>=', $start)
            ->where('end', '<', $end)
            ->get();

        foreach ($appointments as $appointment) {
            foreach ($slots as $key => $slot) {
                if ($appointment->start >= $slot['start'] && $appointment->start < $slot['end']) {
                    unset($slots[$key]);

                    if ($slot['start']->diffInMinutes($appointment->start) != 0 && $slot['end']->diffInMinutes($appointment->end) != 0) {
                        $slots[] = [
                            'start' => $slot['start'],
                            'end' => $appointment->start,
                        ];

                        $slots[] = [
                            'start' => $appointment->end,
                            'end' => $slot['end'],
                        ];
                    }

                    if ($slot['start']->diffInMinutes($appointment->start) == 0 && $slot['end']->diffInMinutes($appointment->end) != 0) {
                        $slots[] = [
                            'start' => $appointment->end,
                            'end' => $slot['end']
                        ];
                    }

                    if ($slot['start']->diffInMinutes($appointment->start) != 0 && $slot['end']->diffInMinutes($appointment->end) == 0) {
                        $slots[] = [
                            'start' => $slot['start'],
                            'end' => $appointment->start
                        ];
                    }
                }
            }
        }

        return $slots;
    }

    /**
     * Add all possible appointments in time slot
     *
     * @param array $slot
     * @param int $duration
     *
     * @return array
     */
    protected function addPossibleAppointmentsFromSlot(array $slot, int $duration)
    {
        $options = [];
        $start = Carbon::createFromTimestamp($slot['start']->timestamp);

        while ($start->addMinutes($duration) <= $slot['end']) {
            $options[] = [
                'start' => Carbon::createFromTimestamp($start->subMinutes($duration)->timestamp),
                'end' => Carbon::createFromTimestamp($start->addMinutes($duration)->timestamp),
            ];
        }

        return $options;
    }
}
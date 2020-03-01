<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\GetAppointmentsRequest;
use App\Models\Appointment;
use App\Models\Therapist;
use App\Repositories\Contracts\AppointmentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AppointmentController extends Controller
{
    /**
     * @var AppointmentRepository
     */
    protected $appointmentRepository;

    /**
     * AppointmentController constructor.
     *
     * @param AppointmentRepository $appointmentRepository
     */
    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Get available appointments
     *
     * @param GetAppointmentsRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAvailableAppointments(GetAppointmentsRequest $request)
    {
        // fixed therapists - only for test
        $therapist = Therapist::find(1);

        $duration = ($request->query('duration'))
            ? $request->query('duration')
            : Appointment::DEFAULT_DURATION;

        $date = ($request->query('date'))
            ? new Carbon($request->query('date'))
            : Carbon::now()->addDay();

        $availableAppointments = $this->appointmentRepository->getAvailableAppointments($duration, $date, $therapist);

        return view('available-appointments', compact(
            'availableAppointments',
            'duration',
            'therapist',
            'date')
        );
    }

    /**
     * Create new appointment
     *
     * @param CreateAppointmentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAppointment(CreateAppointmentRequest $request)
    {
        $start = new Carbon($request['start']);

        $this->appointmentRepository->create([
            'start' => $request['start'],
            'end' => $start->addMinutes($request['duration']),
            'therapist_id' => $request['therapist_id'],
        ]);

        Session::flash('message', trans('appointments.save_success'));

        return back();
    }
}

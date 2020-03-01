<h1>@lang('appointments.heading')</h1>

@if (Session::has('message'))
    <p class="alert alert-info">{{ Session::get('message') }}</p>
@endif

<form action="{{action('AppointmentController@getAvailableAppointments')}}" method="GET">

    @error('date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <label>@lang('appointments.date')</label>
    <input type="date" name="date" value="{{$date->toDateString()}}"><br>

    @error('duration')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <label>@lang('appointments.duration')</label>
    @foreach (\App\Models\Appointment::DURATION_OPTIONS as $durationOption)
        <input type="radio" name="duration" value="{{$durationOption}}" @if ($durationOption == $duration) checked="checked" @endif>{{$durationOption}}
    @endforeach
    <br>

    <button type="submit">@lang('appointments.get')</button>
</form>

@if (!empty($availableAppointments))
    <form method="POST" action="{{action('AppointmentController@createAppointment')}}">
        @csrf

        <input type="hidden" name="duration" value="{{$duration}}">
        <input type="hidden" name="therapist_id" value="{{$therapist->id}}">

        @foreach ($availableAppointments as $availableAppointment)
            <input type="radio" name="start" value="{{$availableAppointment['start']->toDateTimeString()}}">
            {{$availableAppointment['start']->format('H:i')}} - {{$availableAppointment['end']->format('H:i')}}
            <br>
        @endforeach

        <button type="submit">@lang('appointments.create')</button>
    </form>
@else
    @lang('appointments.empty')
@endif
<?php

namespace App\Providers;

use App\Repositories\Contracts\AppointmentRepository;
use App\Repositories\Contracts\TherapistRepository;
use App\Repositories\Eloquent\AppointmentRepositoryEloquent;
use App\Repositories\Eloquent\TherapistRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(TherapistRepository::class, TherapistRepositoryEloquent::class);
        $this->app->bind(AppointmentRepository::class, AppointmentRepositoryEloquent::class);
    }
}
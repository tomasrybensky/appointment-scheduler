<?php

namespace App\Repositories\Eloquent;

use App\Models\Therapist;
use App\Repositories\Contracts\TherapistRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class TherapistRepositoryEloquent extends BaseRepository implements TherapistRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Therapist::class;
    }
}
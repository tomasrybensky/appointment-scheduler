<?php

use Illuminate\Database\Seeder;
use App\Models\Therapist;


class TherapistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Therapist::all()->count() == 0) {
            factory(Therapist::class)->create();
        }
    }
}

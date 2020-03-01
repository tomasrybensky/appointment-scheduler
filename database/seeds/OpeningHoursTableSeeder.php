<?php

use Illuminate\Database\Seeder;

class OpeningHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $therapist = \App\Models\Therapist::all()->first;

        if (isset($therapist) && empty($therapist->openingHours)) {
            factory(\App\Models\OpeningHours::class)->create([
                'therapist_id' => $therapist->id,
                'open' => '8:30',
                'close' => '12:00',
            ]);

            factory(\App\Models\OpeningHours::class)->create([
                'therapist_id' => $therapist->id,
                'open' => '13:00',
                'close' => '18:30',
            ]);
        }
    }
}

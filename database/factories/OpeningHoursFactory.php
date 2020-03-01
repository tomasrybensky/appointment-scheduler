<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\OpeningHours::class, function (Faker $faker) {
    return [
        'therapist_id' => factory(\App\Models\Therapist::class)->create()->id,
        'open' => '8:30',
        'close' => '16:00'
    ];
});

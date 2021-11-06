<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \App\Reservation;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'playground_id' => factory(\App\User::class)->create(['role' => 'user'])->id,
        'user_id'       => factory(\App\User::class)->create(['role' => 'playground'])->id,
        'day'           => today()->addDays(3)->toDateString(),
        'start'         => today()->toTimeString(),
        'end'           => today()->toTimeString(),
    ];
});

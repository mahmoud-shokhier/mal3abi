<?php

/** @var Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'role'           => User::All[array_rand(User::All)],
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'phone'          => '01096206373',
        'password'       => '123456789',
        'remember_token' => Str::random(10),
    ];
});

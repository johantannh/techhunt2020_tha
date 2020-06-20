<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use Faker\Generator as Faker;
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

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->numerify('e#####'),
        'login' => $faker->unique()->word,
        'name' => $faker->name,
        'salary' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 10000000)
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Event;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'academic_year' => $faker->numberBetween(2015, 2019),
        'date_start' => Carbon::now(),
    ];
});

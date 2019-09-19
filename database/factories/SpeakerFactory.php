<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Speaker;
use Faker\Generator as Faker;

$factory->define(Speaker::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'description' => $faker->text(100),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'id' => 'STUDENT-' . $faker->phoneNumber,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_initial' => 'A'
    ];
});

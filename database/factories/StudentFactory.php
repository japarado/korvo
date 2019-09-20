<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'student_id' => $faker->ean13,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_initial' => strtoupper($faker->randomLetter)
    ];
});

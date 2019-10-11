<?php

use App\Event;
use App\Student;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::all()->each(function($event) {
            $involvement_types = ['Participant', 'Organizer'];
            factory(Student::class, 100)->create()->each(function($student) use ($event, $involvement_types) {
                $event->students()->attach($student->id, ['involvement' => $involvement_types[array_rand($involvement_types)]]);
            });
        });
    }
}

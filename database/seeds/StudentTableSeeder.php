<?php

use App\Event;
use App\Student;
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
            factory(Student::class, 5)->create()->each(function($student) use ($event) {
                $event->students()->attach($student->id);
            });
        });
    }
}

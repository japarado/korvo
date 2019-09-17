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
        foreach(Event::all() as $event)
        {
            $students = factory(Student::class, 5)->create();
            foreach($students as $student)
            {
                $event->students()->attach($student->id);
                /* $student->save(); */
            }
        }
    }
}

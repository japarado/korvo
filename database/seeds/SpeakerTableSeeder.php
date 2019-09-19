<?php

use App\Event;
use App\Speaker;
use Illuminate\Database\Seeder;

class SpeakerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::all()->each(function($event) {
            factory(Speaker::class, 3)->create()->each(function($speaker) use ($event) {
                $event->speakers()->attach($speaker->id);
            });
        });
    }
}

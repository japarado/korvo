<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Event;
use Illuminate\Support\Facades\Config;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create events for organization users
        User::where('role_id', Config::get('constants.roles.organization'))->get()->each(function($user) {
            $role_user = $user->organization;
            $role_user->events()->save(factory(Event::class)->make());
        });

        // Create events for socc users
        /* User::where('role_id', Config::get('constants.roles.socc'))->get()->each(function($user) { */
        /*     $role_user = $user->socc; */
        /*     $role_user->events()->save(factory(Event::class)->make()); */
        /* }); */

        // Create events for osa users
        /* User::where('role_id', Config::get('constants.roles.osa'))->get()->each(function($user) { */
        /*     $role_user = $user->organization; */
        /*     $role_user->events()->save(factory(Event::class)->make()); */
        /* }); */
    }
}

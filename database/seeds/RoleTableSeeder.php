<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'name' => 'Organization',
            'description' => 'Generic organization role, creates events'
        ]);
        DB::table('role')->insert([
            'name' => 'SOOC',
            'description' => 'Approves events submitted by the students'
        ]);
        DB::table('role')->insert([
            'name' => 'OSA',
            'description' => 'Approves events that have passed the SOOC\'s scrutiny'
        ]);
    }
}

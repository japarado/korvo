<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

use App\User;
use App\Organization;
use App\SOCC;
use App\OSA;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the OSA users
        $osa_user = User::create([
            'first_name' => 'OSA',
            'last_name' => 'User',
            'email' => 'osa@mail.com',
            'password' => Hash::make('secret'),
            'role_id' => Config::get('constants.roles.osa')
        ]);
        
        OSA::create([
            'user_id' => $osa_user->id
        ]);

        // Create organization users
        $org_user_one = User::create([
            'first_name' => 'Org ',
            'last_name' => 'One',
            'email' => 'org1@mail.com',
            'student_number' => 'STU1',
            'password' => Hash::make('secret'),
            'role_id' => Config::get('constants.roles.organization')
        ]);
        $org_user_two = User::create([
            'first_name' => 'Org ',
            'last_name' => 'Two',
            'email' => 'org2@mail.com',
            'student_number' => 'STU2',
            'password' => Hash::make('secret'),
            'role_id' => 1
        ]);

        // Create an org for each org user
        Organization::create([
            'user_id' => $org_user_one->id,
            'name' => 'ORG ONE',
            'type' => 'TYPE A',
            'college' => 'COLLEGE ONE'
        ]);
        Organization::create([
            'user_id' => $org_user_two->id,
            'name' => 'ORG TWO',
            'type' => 'TYPE B',
            'college' => 'COLLEGE TWO'
        ]);

        // Create SOCC users 
        $socc_user_one = User::create([
            'first_name' => 'SOCC ',
            'last_name' => 'One',
            'email' => 'socc1@mail.com',
            'student_number' => 'STU3',
            'password' => Hash::make('secret'),
            'role_id' => Config::get('constants.roles.socc')
        ]);
        $socc_user_two = User::create([
            'first_name' => 'SOCC ',
            'last_name' => 'Two',
            'email' => 'socc2@mail.com',
            'student_number' => 'STU4',
            'password' => Hash::make('secret'),
            'role_id' => Config::get('constants.roles.socc')
        ]);

        SOCC::create([
            'user_id' => $socc_user_one->id
        ]);

        SOCC::create([
            'user_id' => $socc_user_two->id
        ]);
    }
}

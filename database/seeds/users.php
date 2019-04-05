<?php

use Illuminate\Database\Seeder;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [];
        for($i = 1; $i < 20; $i++) {
            $users[] = [
                'name' => str_random(10),
                'email' => str_random(12).'@gmail.com',
                'email_verified_at' => now(),
                'password' => str_random(22), // secret
                'remember_token' => str_random(10),
            ];
        }
        \Illuminate\Support\Facades\DB::table('users')->insert($users);

    }
}

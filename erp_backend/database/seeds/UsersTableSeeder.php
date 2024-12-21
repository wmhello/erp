<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'avatar' => 'uploads/201711251441th5a19812148058.jpg',
            'remember_token' => str_random(10),
            'created_at' => \Carbon\Carbon::now(),
        ]);
    }
}

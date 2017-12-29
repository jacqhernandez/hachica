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
        'name' => 'Admin',
        'username' => 'hachica',
        'email' => 'jacquelyn.hernandez7@gmail.com',
        'password' => bcrypt('secret'),
    	]);
    }
}

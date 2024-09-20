<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('users')->insert(
            array(
                'name'      => env('ADMIN_NAME'),
                'email'     => env('ADMIN_EMAIL'),
                'password'  => bcrypt(env('ADMIN_PASSWORD')),
                'type'  => 'admin'
            )
        );
    }
}

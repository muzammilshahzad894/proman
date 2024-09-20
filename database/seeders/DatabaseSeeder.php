<?php

use Database\Seeders\EmailTemplatesSeeder;
use Database\Seeders\SettingsTableSeeder;
use Database\Seeders\StaticPagesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(EmailTemplatesSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(StaticPagesTableSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaticPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample
        DB::table('static_pages')->truncate();

        DB::table('static_pages')->insert([
            'title' => 'Terms and Conditions',
            'content' => '<h2>Terms and Conditions</h2><p>This is where you can add "Terms &amp; Conditions" for rental reservations.</p>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        DB::table('static_pages')->insert([
            'title' => 'Privacy Policy',
            'content' => '<h2>Privacy Policy</h2><p>This is where you add your "Privacy Policy" information.</p>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('static_pages')->insert([
            'title' => 'Sample Page',
            'content' => '<h2>Sample Page</h2><p>This is sample page.</p>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

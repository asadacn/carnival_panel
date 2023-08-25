<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            'title' => '7 Mbps',
            'price' => '525',

        ]);

        DB::table('packages')->insert([
            'title' => '10 Mbps',
            'price' => '699',

        ]);
        DB::table('packages')->insert([
            'title' => '12 Mbps',
            'price' => '699',

        ]);
        DB::table('packages')->insert([
            'title' => '15 Mbps',
            'price' => '1049',

        ]);
        DB::table('packages')->insert([
            'title' => '20 Mbps',
            'price' => '1260',

        ]);
        DB::table('packages')->insert([
            'title' => '20 Mbps_RP',
            'price' => '1558',

        ]);
        DB::table('packages')->insert([
            'title' => '15 Mbps_RP',
            'price' => '1400',

        ]);
        DB::table('packages')->insert([
            'title' => '10 Mbps_RP',
            'price' => '998',

        ]);
    }
}

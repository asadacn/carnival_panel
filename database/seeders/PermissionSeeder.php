<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => 'super',
            'slug' => 'super',

        ]);
        DB::table('user_has_permissions')->insert([
            'user_id' => '1',
            'permission_id' => '1',

        ]);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Retailer',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Wholesaler',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'FMCG',
            'guard_name' => 'api',
        ]);
    }
}

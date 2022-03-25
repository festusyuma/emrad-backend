<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Beverages',
            'slug' => 'beverages',
            'description' => '',
        ]);

        DB::table('categories')->insert([
            'name' => 'Packaged Food',
            'slug' => 'packaged_food',
            'description' => '',
        ]);

        DB::table('categories')->insert([
            'name' => 'Spirits & Wines',
            'slug' => 'spirits_and_wines',
            'description' => '',
        ]);

        DB::table('categories')->insert([
            'name' => 'Cereal',
            'slug' => 'cereal',
            'description' => '',
        ]);
    }
}

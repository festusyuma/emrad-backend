<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(OffersTableSeeder::class);
        $this->call(RetailerOrdersTableSeeder::class);
        $this->call(RetailerInventoriesTableSeeder::class);
    }
}

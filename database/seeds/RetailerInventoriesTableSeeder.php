<?php

use Emrad\Models\RetailerInventory;
use Illuminate\Database\Seeder;

class RetailerInventoriesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    $retailerInventories = [
      [
        'product_id' => 2,
        'quantity' => 2,
        'cost_price' => 5000,
        'selling_price' => 7000,
        'in_stock' => 1,
      ],

      [
        'product_id' => 3,
        'quantity' => 6,
        'cost_price' => 12900,
        'selling_price' => 17000,
        'in_stock' => 1,
      ],
    ];

    foreach ($retailerInventories as $retailerInventory) {
      RetailerInventory::create($retailerInventory);
    }
  }
}

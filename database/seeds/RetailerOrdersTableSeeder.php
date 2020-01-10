<?php

use Emrad\Models\RetailerOrder;
use Illuminate\Database\Seeder;

class RetailerOrdersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(\Emrad\Models\RetailerOrder::class, 50)->create();

    //
      // $retailerOrders = [
      //   [
      //     'product_id' => 2,
      //     'company_id' => 2,
      //     'quantity' => 5,
      //     'unit_price' => 5000,
      //     'order_amount' => 3,
      //     'created_by' => 'Ade Anselm'
      //   ],

      //   [
      //     'product_id' => 3,
      //     'company_id' => 3,
      //     'quantity' => 4,
      //     'unit_price' => 14000,
      //     'order_amount' => 5,
      //     'created_by' => 'Yemi Larry'
      //   ],
      // ];

      // foreach ($retailerOrders as $retailerOrder) {
      //   RetailerOrder::create($retailerOrder);
      // }
  }
}

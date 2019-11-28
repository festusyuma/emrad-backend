<?php

use Emrad\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    $products = [
      [
        'name' => 'Milo BigTin',
        'description' => 'Big tin size milo',
        'size' => 'Big',
        'price' => 1200,
      ],

      [
        'name' => 'Nescafe slimBlack',
        'description' => 'small slim black nescafe coffee',
        'size' => 'small',
        'price' => 200,
      ],

      [
        'name' => 'Hypo BigBottle',
        'description' => 'Big bottle size hypo',
        'size' => 'Big',
        'price' => 1800,
      ],
    ];

    foreach ($products as $product) {
      Product::create($product);
    }
  }
}

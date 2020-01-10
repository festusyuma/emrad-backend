<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\RetailerInventory;
use Faker\Generator as Faker;

$factory->define(RetailerInventory::class, function (Faker $faker) {
    return [
        "product_id" => factory(\Emrad\Models\Product::class, 1)->create()->first()->id,
        "quantity" => $faker->numberBetween(1, 10),
        "cost_price" => $faker->numberBetween(1000, 3000),
        "selling_price" => $faker->numberBetween(1000, 9000),
        "is_in_stock" => 1
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\RetailerOrder;
use Faker\Generator as Faker;

$factory->define(RetailerOrder::class, function (Faker $faker) {
    return [
        "product_id" => factory(\Emrad\Models\Product::class, 1)->create()->first()->id,
        "company_id" => factory(\Emrad\Models\Company::class, 1)->create()->first()->id,
        "quantity" => $faker->numberBetween(1, 10),
        "unit_price" => $faker->numberBetween(1000, 3000),
        "selling_price" => $faker->numberBetween(2000, 5000),
        "order_amount" => $faker->numberBetween(1000, 9000),
        "created_by" => $faker->numberBetween(1,10),
        "is_confirmed" => 1
    ];
});

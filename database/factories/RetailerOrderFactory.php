<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\RetailerOrder;
use Faker\Generator as Faker;

$factory->define(RetailerOrder::class, function (Faker $faker) {
    return [
        "product_id" => $faker->numberBetween(1,50),
        "company_id" => $faker->numberBetween(1,15),
        "quantity" => $faker->numberBetween(1, 10),
        "unit_price" => $faker->numberBetween(1000, 3000),
        "selling_price" => $faker->numberBetween(2000, 5000),
        "order_amount" => $faker->numberBetween(1000, 9000),
        "created_by" => $faker->numberBetween(1,10),
        "is_confirmed" => 1
    ];
});

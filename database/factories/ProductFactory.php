<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        "name" => $faker->firstNameMale,
        "category_id" => factory(\Emrad\Models\Category::class, 1)->create()->first()->id,
        "user_id" => factory(\Emrad\User::class, 1)->create()->first()->id,
        "sku" => base_convert(microtime(true), 10, 36),
        "description" => $faker->sentence(20),
        "image" => "https://dummyimage.com/100x100/fff/0000",
        "size" => $faker->numberBetween(3, 10),
        "price" => $faker->numberBetween(1000, 3000),
        "selling_price" => $faker->numberBetween(100, 1000)
    ];
});

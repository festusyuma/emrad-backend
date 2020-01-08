<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "category_id" => factory(\Emrad\Models\Category::class, 1)->create()->first()->id,
        "description" => $faker->sentence(20),
        "image" => $faker->imageUrl(),
        "size" => $faker->numberBetween(3, 10),
        "price" => $faker->numberBetween(1000, 3000)
    ];
});

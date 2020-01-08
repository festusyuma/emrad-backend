<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\Offer;
use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        "product_id" => factory(\Emrad\Models\Product::class, 1)->create()->first()->id,
        "title" => $faker->sentence(10),
        "image" => $faker->imageUrl(),
        "description" => $faker->sentence(30),
        "start_date" => $faker->date(),
        "end_date" => $faker->date(),
    ];
});

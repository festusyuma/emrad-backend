<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Emrad\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $name = str_random(3);
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'description' => $faker->sentence(10),
        'logo' => $faker->imageUrl(),
    ];
});

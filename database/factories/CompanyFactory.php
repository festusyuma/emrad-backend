<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Emrad\Models\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "address" => $faker->address,
        "official_mail" => $faker->email,
        "cac" => $faker->numberBetween(1000,99999),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Emrad\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName("male"),
        'company_id' => factory(\Emrad\Models\Company::class, 2)->create()->first()->id,
        'last_name' => $faker->firstName("male"),
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $faker->phoneNumber,
        'avater' => $faker->imageUrl(),
        'address' => $faker->address,
        'gender' => "male",
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

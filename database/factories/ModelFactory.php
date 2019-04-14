<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Item;
use App\User;
use Faker\Generator;

$factory->define(User::class, function (Generator $faker) {
    return [
        'firstName'  => $faker->firstName,
        'lastName'   => $faker->lastName,
        'middleName' => $faker->randomLetter,
        'email'      => $faker->email,
        'password'   => $faker->password,
    ];
});

$factory->define(Item::class, function (Generator $faker) {
    return [
        'itemName'    => $faker->text(10),
        'imageUrl'    => $faker->url,
        'cost'        => $faker->numberBetween(10, 200),
        'quantity'    => $faker->numberBetween(1, 10),
        'description' => $faker->sentence,
        'categoryID'  => 1,
    ];
});

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Movie::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->realText(50),
        'rating'      => $faker->optional()->numberBetween(0, 100),
        'description' => $faker->optional()->paragraph(3),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Actor::class, function (Faker\Generator $faker) {
    return [
        'first_name'  => $faker->firstName(),
        'middle_name' => $faker->optional()->firstName(),
        'last_name'   => $faker->lastName,
        'dob'         => $faker->optional()->date($format = 'Y-m-d', $max = 'now'),
        'biography'   => $faker->optional()->paragraph(3),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Genre::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word(),
    ];
});

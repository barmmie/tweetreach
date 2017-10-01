<?php

use Faker\Generator as Faker;

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

$factory->define(App\Tweet::class, function (Faker $faker) {
    $now = Carbon\Carbon::now();
    return [
        'tweet_id' => $faker->randomNumber(7),
        'total_follower_count' => $faker->numberBetween( 1000, 9000),
        'created_at' => $now,
        'updated_at' => $now,
    ];
});

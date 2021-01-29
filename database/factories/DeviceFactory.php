<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Device::class, function (Faker $faker) {
    return [
        "uid" => $faker->word(),
        "app_id" => $faker->word(),
        "language" => 'English',
        "operating_system" =>  "ios"
    ];
});

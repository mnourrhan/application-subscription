<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Subscription::class, function (Faker $faker) {
    return [
        "device_id" => (factory(\App\Models\Device::class)->create())->id,
        "expiry_date" => \Carbon\Carbon::now()->addDays(rand(-20,20)),
        "receipt" => $faker->numberBetween(1000, 9999),
    ];
});

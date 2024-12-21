<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Order::class, function (Faker $faker) {
    return [
        //
        'order_number' => $faker->unique()->numerify('order_######'),
        'merchant_number' => $faker->numerify('merchant_###'),
        'merchant_name' => $faker->company(),
        'order_time' => Carbon::now(),
    ];
});

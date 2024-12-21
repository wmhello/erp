<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(App\Models\Product::class, function (Faker $faker) {
    $productAmount = $faker->numberBetween($min = 10, $max =1000);
    return [
        //
        'product_name' => $faker->lexify('product ???'),
        'product_number' => $faker->numerify('product_####'),
        'product_img' => $faker->imageUrl($width = 150, $height = 150),
        'product_amount' =>$productAmount,
        'remain_amount' =>$productAmount,
        'order_id' => $faker->numberBetween($min = 1, $max =50),
        'buy_date' => Carbon::now()
    ];
});

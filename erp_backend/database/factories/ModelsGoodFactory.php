<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Good::class, function (Faker $faker) {
    $source = ['天猫', '淘宝', '当当', '京东', '实体店'];
    return [
        //
        'number'=>$faker->numerify('p###'),
        'name' =>$faker->sentence($nbWords = 3, $variableNbWords = true),
        'source' => $faker->randomElement($source),
        'cate_id' => $faker->numberBetween(1,9),
        'img' => $faker->imageUrl(200,150),
        'cost' => $faker->randomFloat(2,0, 5000),
    ];
});

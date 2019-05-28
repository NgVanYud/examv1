<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->text(40),
        'credit' => $faker->randomDigitNotNull,
        'code' => strtoupper($faker->unique(true)->text(10)),
    ];
});

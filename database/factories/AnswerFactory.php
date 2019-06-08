<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Option::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph(2, true),
        'is_correct' => 0,
    ];
});

<?php


use Illuminate\Support\Str;
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

$factory->define(App\Models\Manager::class, function (Faker $faker) {
  return [
    'username' => $faker->unique()->regexify('MN[0-9]{6}'),
    'code' => strtoupper($faker->unique(true)->text(10)),
    'first_name' => $faker->word,
    'last_name' => $faker->lastName,
    'email' => $faker->unique()->safeEmail,
    'password' => bcrypt('123@abc'), // secret
    'is_actived' => 1
  ];
});

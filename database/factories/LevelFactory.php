<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Level;
use Faker\Generator as Faker;

$factory->define(Level::class, function (Faker $faker) {
    return [
        'name'          =>  $faker->word,
        'description'   =>  $faker->sentence
    ];
});

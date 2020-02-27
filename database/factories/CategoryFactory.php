<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name'  =>  $faker->randomElement(['PHP', 'JAVASCRIPT', 'WEB DESIGN', 'JAVA', 'HTML', 'BASES DE DATOS', 'MYSQL', 'NO SQL', 'NODE JS', 'CSS', 'SASS', 'ANGULAR', 'MONGO DB']),
        'description'   =>  $faker->sentence

    ];
});

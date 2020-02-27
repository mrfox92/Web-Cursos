<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {

    $name = $faker->sentence;
    $status = $faker->randomElement([\App\Course::PUBLISHED, \App\Course::PENDING, \App\Course::REJECTED]);

    return [
        'teacher_id'        =>  \App\Teacher::all()->random()->id,
        'category_id'       =>  \App\Category::all()->random()->id,
        'level_id'          =>  \App\Level::all()->random()->id,
        'name'              =>  $name,
        'slug'              =>  Str::slug($name, '-'),
        'description'       =>  $faker->paragraph,
        'picture'           =>  \Faker\Provider\Image::image(storage_path() . '/app/public/courses', 600, 350, 'tech', false),
        'previous_approved' =>  $status !== \App\Course::PUBLISHED ? false : true,
        'previous_rejected' =>  $status === \App\Course::REJECTED ? true : false

    ];
});

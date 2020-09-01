<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Notification;
use App\Entities\Admin;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'admin_id' => Admin::inRandomOrder()->first()->id,
        'title' => $faker->sentence(3),
        'content' => $faker->sentence(11)
    ];
});

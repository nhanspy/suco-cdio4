<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Entities\Admin;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'password' => Hash::make('password'),
        'avatar' => '/images/default/auth/avatar.jpg'
    ];
});

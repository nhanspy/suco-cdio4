<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Entities\Project;
use App\Entities\Role;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'admin_id' => Role::where('name', 'SuperAdmin')->first()->admins()->inRandomOrder()->first()->id,
        'name' => $faker->city,
        'description' => $faker->text(300),
    ];
});

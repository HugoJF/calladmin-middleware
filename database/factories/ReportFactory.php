<?php

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

$factory->define(App\Report::class, function (Faker $faker) {
    return [
        'server_ip'   => $faker->ipv4,
        'server_port' => $faker->numberBetween(0, 49152),
        'reason'      => $faker->paragraph(5),
        'vip'         => $faker->boolean(30),

        'reporter_name' => $faker->name,
        'target_name'   => $faker->name,
    ];
});

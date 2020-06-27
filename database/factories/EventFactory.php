<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(6, true),
        'description' => $faker->realText(200, 2),
        'date' => now()->addDays(10),
        'ticket_price' => $faker->randomFloat(1, 10, 50),
    ];
});

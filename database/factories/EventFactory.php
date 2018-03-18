<?php

use App\Events\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'amount' => 100,
    ];
});

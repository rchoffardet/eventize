<?php

use App\Events\Event;
use App\Reservations\Reservation;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker, Event $event) {
    return [
        'event_id' => $event->id,
    ];
});

<?php
namespace App\Reservations;

use App\Events\Event;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function  __construct() {
        parent::__construct();
    }

    public static function fromEvent(Model $event)
    {
        $reservation = new self();
        $reservation->event()->associate($event);

        return $reservation;
    }

    // Relations
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
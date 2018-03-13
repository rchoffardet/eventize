<?php
namespace App\Reservations;

use App\Events\Event;
use App\Payments\Amountable;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Reservations\Reservation
 *
 * @property-read \App\Events\Event $event
 * @property-read \App\User $user
 * @mixin \Eloquent
 */
class Reservation extends Model implements Amountable
{
    public static function fromEvent(Model $event)
    {
        $reservation = new self();
        $reservation->event()->associate($event);
        $reservation->amount = $event->amount;

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

    public function toAmount() : int
    {
        return $this->amount;
    }
}

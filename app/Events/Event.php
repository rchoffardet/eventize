<?php
namespace App\Events;

use App\Payments\Amountable;
use App\Reservations\Reservation;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Events\Event
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reservations\Reservation[] $reservations
 * @property int                                                                           $amount
 * @property User[]|\Illuminate\Database\Eloquent\Collection                               subscribers
 * @method static count
 * @mixin \Eloquent
 */
class Event extends Model implements Amountable
{
    // Relations

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class);
    }

    public function toAmount() : int
    {
        return $this->amount;
    }
}

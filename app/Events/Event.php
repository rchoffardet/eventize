<?php
namespace App\Events;

use App\Payments\Amountable;
use App\Reservations\Reservation;
use Illuminate\Database\Eloquent\Model;

class Event extends Model implements Amountable
{
    // Relations

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    public function toAmount() : int
    {
        return  $this->amount;
    }
}

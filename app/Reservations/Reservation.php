<?php
namespace App\Reservations;

use App\Events\Event;
use App\Payments\Payable;
use App\Payments\Payment;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Reservations\Reservation
 *
 * @property-read \App\Events\Event $event
 * @property-read \App\User $user
 * @property int $amount
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @mixin \Eloquent
 */
class Reservation extends Model implements Payable
{
    public static function fromEventAndUser(Event $event, User $user)
    {
        $reservation = new static();
        $reservation->event()->associate($event);
        $reservation->user()->associate($user);
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

    // Business logic

    public function check(Payment $payment)
    {
        if($payment->isSuccessful())
        {
            $this->paid_at = $payment->succeedAt();
        }
    }

    public function isPaid()
    {
        return $this->paid_at !== null;
    }

    // Contracts

    public function toAmount() : int
    {
        return $this->amount;
    }

    public function pay()
    {
        $this->paid_at = now();
    }

    // Scopes

    public function scopePaid(Builder $query)
    {
        $query->whereNotNull('paid_at');
    }

    public function scopeNotPaid(Builder $query)
    {
        $query->whereNull('paid_at');
    }

}

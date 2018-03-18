<?php

namespace App;

use App\Events\Event;
use App\Exceptions\AlreadyRegisteredException;
use App\Notifications\UserRegistered;
use App\Payments\Payment;
use App\Reservations\Reservation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \App\Reservations\Reservation $reservations
 * @property-read \App\Payments\Payment         $payments
 * @property string                             password
 * @property string                             registration_token
 * @property int                                id
 * @method static count
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Business logic

    /**
     * @param $email
     *
     * @return \App\User
     */
    public static function RegisterWithEmail($email)
    {
        $user = self::create([
            'name'               => $email,
            'email'              => $email,
            'registration_token' => str_random(32),
        ]);

        $user->notify(new UserRegistered($user));

        return $user;
    }

    public function book(Reservation $reservation)
    {
        $reservation->user()->associate($this);
    }

    public function canLogin()
    {
        return $this->password != null
               && $this->registration_token == null;
    }

    public function confirmRegistration($registrationToken, $newPassword)
    {
        if($this->registration_token == null)
        {
            throw new AlreadyRegisteredException();
        }

        if($this->registration_token == $registrationToken)
        {
            $this->password           = $newPassword;
            $this->registration_token = null;

            return true;
        }

        return false;
    }

    public function createEvent($attributes = [])
    {
        $attributes['user_id'] = $this->id;

        return Event::create($attributes);
    }

    public function subscribeToEvent(Event $event)
    {
        $event->subscribers()->attach($this->id);
    }
}

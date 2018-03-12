<?php
namespace Tests\Unit\Reservations;

use App\Events\Event;
use App\Reservations\Reservation;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reservation_can_be_created_from_event()
    {
        // Arrange
        $event = new Event();
        $event->save();

        // Act
        $reservation = Reservation::fromEvent($event);

        // Assert
        $this->assertEquals($event->id, $reservation->event_id);
    }

    /** @test */
    public function reservation_can_be_paid()
    {
        // Arrange
        $reservation = new Reservation();

        // Act
        $reservation->paid_at = now();

        // Assert
        $this->assertTrue($reservation->isPaid());
    }

    /** @test */
    public function reservation_can_be_paid_by_an_user()
    {
        // Arrange
        $reservation = new Reservation();
        $reservation->amount = 100;
        $user = new User();

        // Act
        $reservation->paidBy($user);

        // Assert
        $this->assertTrue($reservation->isPaid());

    }
}

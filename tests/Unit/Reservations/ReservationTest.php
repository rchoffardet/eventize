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
    public function reservation_compute_correct_price_from_event()
    {
        // Arrange
        $event = new Event(['amount' => 100]);

        // Act
        $reservation = Reservation::fromEvent($event);

        // Assert
        $this->assertEquals($event->amount, $reservation->amount);
    }
}

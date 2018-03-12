<?php
namespace Tests\Unit;

use App\Events\Event;
use App\Reservations\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_book_a_reservation()
    {
        // Arrange
        $event = new Event();
        $reservation = Reservation::fromEvent($event);
        $user = new User();

        // Act
        $user->book($reservation);

        // Assert
        $this->assertEquals($user->id, $reservation->user_id);
    }
}

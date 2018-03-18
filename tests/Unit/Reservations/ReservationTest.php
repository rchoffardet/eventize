<?php
namespace Tests\Unit\Reservations;

use App\Events\Event;
use App\Payments\Gateways\FakePaymentGateway;
use App\Payments\Payment;
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
        $user = factory(User::class)->create();
        $event = new Event(['amount' => 100]);
        $event->save();

        // Act
        $reservation = Reservation::fromEventAndUser($event, $user);

        // Assert
        $this->assertEquals($event->id, $reservation->event_id);
    }

    /** @test */
    public function reservation_compute_correct_price_from_event()
    {
        // Arrange
        $event = new Event(['amount' => 100]);
        $user = new User();

        // Act
        $reservation = Reservation::fromEventAndUser($event, $user);

        // Assert
        $this->assertEquals($event->amount, $reservation->amount);
    }

    /** @test */
    public function reservation_can_be_paid()
    {
        // Arrange
        $user = new User();
        $reservation = Reservation::fromEventAndUser(new Event(['amount' => 100]), $user);
        $gateway = new FakePaymentGateway();
        $payment = new Payment($gateway, $user, $reservation);
        $payload = null;
        $payment->execute($payload);

        // Act
        $reservation->check($payment);

        // Assert
        $this->assertTrue($reservation->isPaid());
    }

    /** @test */
    public function paid_reservations_can_be_explicitly_counted()
    {
        // Arrange
        $user = factory(User::class)->create();
        $gateway = new FakePaymentGateway();
        $payload = null;
        $event = factory(Event::class)->create();

        // Act
        $reservation = Reservation::fromEventAndUser($event, $user);
        $payment = new Payment($gateway, $user, $reservation);
        $payment->execute($payload);
        $reservation->save();

        $reservation = Reservation::fromEventAndUser($event, $user);
        $payment = new Payment($gateway, $user, $reservation);
        $payment->execute($payload);
        $reservation->save();

        $reservation = Reservation::fromEventAndUser($event, $user);
        $reservation->save();

        // Assert
        $this->assertEquals(2, Reservation::Paid()->count());
        $this->assertEquals(1, Reservation::NotPaid()->count());

    }
}

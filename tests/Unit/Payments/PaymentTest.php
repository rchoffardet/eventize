<?php
namespace Tests\Unit\Payments;

use App\Events\Event;
use App\Payments\Gateways\FakePaymentGateway;
use App\Payments\Payment;
use App\Reservations\Reservation;
use App\User;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    /** @test */
    public function payment_can_be_made_by_user()
    {
        // Arrange
        $user = new User();
        $amountable = new Event(['amount' => 100]);
        $paymentGateway = new FakePaymentGateway();
        $payment = new Payment($paymentGateway, $user, $amountable);
        $payload = null;

        // Act
        $payment->execute($payload);

        // Assert
        $this->assertTrue($payment->isSuccessful());

    }
}

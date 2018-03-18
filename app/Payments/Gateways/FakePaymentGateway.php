<?php
namespace App\Payments\Gateways;

use App\Payments\Payment;

class FakePaymentGateway implements PaymentGateway
{
    public function verify(Payment $payment, $payload)
    {
        return true;
    }
}

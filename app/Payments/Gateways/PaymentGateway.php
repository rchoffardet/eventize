<?php
namespace App\Payments\Gateways;

use App\Payments\Payment;

interface PaymentGateway
{
    public function verify(Payment $payment, $payload);
}
<?php
namespace App\Payments;

use App\Payments\Gateways\PaymentGateway;
use App\User;
use Illuminate\Contracts\Support\Arrayable;

class Payment implements Arrayable
{
    private $gateway;
    private $succeed_at;
    private $amountable;
    private $user;

    public function __construct(PaymentGateway $gateway, User $user, Amountable $amountable)
    {
        $this->gateway = $gateway;
        $this->amountable = $amountable;
        $this->user = $user;
    }

    public function execute($payload)
    {
        $this->gateway->verify($this, $payload);
    }

    public function amount()
    {
        return $this->amountable->toAmount();
    }

    public function succeed()
    {
        $this->succeed_at = now();
    }

    public function isSuccessful()
    {
        return $this->succeed_at != null;
    }

    public function toArray()
    {
        return [
            'user_id'       => $this->user->id,
            'amount'        => $this->amount(),
            'succeed_at'    => $this->succeed_at,
        ];
    }
}

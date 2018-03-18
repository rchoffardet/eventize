<?php
namespace App\Payments;

use App\Payments\Gateways\PaymentGateway;
use App\User;
use Illuminate\Contracts\Support\Arrayable;

class Payment implements Arrayable
{
    private $gateway;
    private $succeed_at;
    private $payable;
    private $user;

    public function __construct(PaymentGateway $gateway, User $user, Payable $payable)
    {
        $this->gateway = $gateway;
        $this->payable = $payable;
        $this->user    = $user;
    }

    public function execute($payload)
    {
        if($this->gateway->verify($this, $payload))
        {
            $this->succeed();
            $this->payable->pay();
        }
    }

    public function amount()
    {
        return $this->payable->toAmount();
    }

    public function succeed()
    {
        $this->succeed_at = now();
    }

    public function succeedAt()
    {
        return $this->succeed_at;
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

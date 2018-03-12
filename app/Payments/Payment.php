<?php
namespace App\Payments;

use App\Payments\Gateways\PaymentGateway;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    private $gateway;

    public function __construct(PaymentGateway $gateway, User $user, Amountable $amountable)
    {
        $this->gateway = $gateway;

        parent::__construct([
            'amount' => $amountable->toAmount(),
            'user_id' => $user->id,
        ]);
    }

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Business logic

    public function execute($payload)
    {
        $this->gateway->verify($this, $payload);
    }

    public function isSuccessful()
    {
        return $this->succeed_at != null;
    }

    public function succeed()
    {
        $this->succeed_at = now();
    }
}

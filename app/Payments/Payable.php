<?php
namespace App\Payments;

interface Payable extends Amountable
{
    public function pay();
}
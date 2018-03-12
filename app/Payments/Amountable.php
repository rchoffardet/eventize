<?php
namespace App\Payments;

interface Amountable
{
    public function toAmount() : int;
}
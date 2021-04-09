<?php

namespace App\Entity;

class Price
{
    protected float $amount;
    protected string $currency;


    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

}
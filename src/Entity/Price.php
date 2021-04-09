<?php

namespace App\Entity;

class Price
{
    protected float $amount;
    protected string $currency;

    
    public function __construct(string $price)
    {
        $knownCurrencies = ['£'];
        
        if (preg_match('/('.implode('|',$knownCurrencies).')(.*)/', $price, $m) !== 1) {
            throw new \InvalidArgumentException("Unknown price format in '$price'!", 1);
        }

        $currency = $m[1];
        $amount = $m[2];
        
        if (floatval($amount) != $amount) {
            throw new \InvalidArgumentException("Unknown price amount in '$price'!", 2);
        }
        
        if (!in_array($currency, $knownCurrencies)) {
            throw new \InvalidArgumentException("Unknown price currency in '$price'!", 2);
        }
        
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
        
}
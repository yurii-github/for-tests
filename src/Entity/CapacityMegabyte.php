<?php

namespace App\Entity;

class CapacityMegabyte
{
    protected int $amount;


    public function __construct(int $amountInMegabytes)
    {
        $this->amount = $amountInMegabytes;
    }


    public function getAmount(): int
    {
        return $this->amount;
    }


    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $amount = $this->getAmount() > 1000 ? round($this->getAmount() / 1000) : $this->getAmount();
        $size = $this->getAmount() > 1000 ? 'GB' : 'MB';

        return sprintf('%s%s', $amount, $size);
    }
}
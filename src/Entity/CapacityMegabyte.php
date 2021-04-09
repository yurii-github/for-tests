<?php

namespace App\Entity;

class CapacityMegabyte
{
    protected int $amount;


    public function __construct(int $amount)
    {
        $this->amount = $amount;
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
        return sprintf('%s%s',
            $this->getAmount() > 1000 ? round($this->getAmount() / 1000) : $this->getAmount(),
            $this->getAmount() > 1000 ? 'GB' : 'MB'
        );
    }
}
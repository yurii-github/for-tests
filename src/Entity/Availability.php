<?php

namespace App\Entity;

class Availability
{
    protected bool $isAvailable;
    protected string $text;


    public function __construct(string $availability)
    {
        $knownAvailabilities = [
            'out of stock' => false,
            'in stock at b90 4sb' => true,
            'in stock online' => true,
            'in stock' => true,
        ];

        $availability = strtolower($availability);
        $pattern = '/availability:\s?(' . implode('|', array_keys($knownAvailabilities)) . ')/';

        if (preg_match($pattern, $availability, $m) !== 1) {
            throw new \InvalidArgumentException("Unknown availability format in '$availability'!", 1);
        }

        $text = $m[1];

        if (!array_key_exists($text, $knownAvailabilities)) {
            throw new \InvalidArgumentException("Unknown availability status in '$availability'!", 2);
        }

        $this->text = $text;
        $this->isAvailable = $knownAvailabilities[$text];
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getText(): string
    {
        if ($this->text === 'in stock at b90 4sb') {
            return 'In Stock At B90 4SB';
        }
        return ucwords($this->text);
    }
}
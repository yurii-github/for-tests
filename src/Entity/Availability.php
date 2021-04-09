<?php

namespace App\Entity;

class Availability
{
    protected bool $isAvailable;
    protected string $text;


    public function __construct(string $text, bool $isAvailable)
    {
        $this->text = $text;
        $this->isAvailable = $isAvailable;
    }


    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }


    public function getText(): string
    {
        return ucwords($this->text);
    }
}
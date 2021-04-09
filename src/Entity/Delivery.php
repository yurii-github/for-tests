<?php

namespace App\Entity;

use Carbon\Carbon;

class Delivery
{
    protected string $text;
    protected ?Carbon $date;


    public function __construct(string $text, ?Carbon $date)
    {
        $this->text = $text;
        $this->date = $date;
    }


    public function getText(): string
    {
        return $this->text;
    }


    public function getDate(): ?Carbon
    {
        return $this->date;
    }

}
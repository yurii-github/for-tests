<?php

namespace App\Entity;

class Product implements \JsonSerializable
{
    protected const SUPPORTED_COLORS = [
        'green', 'black', 'blue', 'sky blue', 'grey', 'orange', 'white', 'yellow', 'slate grey'
    ];

    protected string $title;
    protected string $colour;
    protected CapacityMegabyte $capacity;
    protected Price $price;
    protected string $imageUrl;
    protected Availability $availability;
    protected ?Delivery $delivery;


    public function getTitle(): string
    {
        return $this->title;
    }


    public function setTitle(string $title)
    {
        $this->title = $title;
    }


    public function setColour(string $colour)
    {
        if (!in_array($colour, static::SUPPORTED_COLORS)) {
            throw new \InvalidArgumentException("Colour '$colour' is not supported!");
        }
        $this->colour = $colour;
    }


    public function getColour(): string
    {
        return $this->colour;
    }

    public function getCapacity(): CapacityMegabyte
    {
        return $this->capacity;
    }


    public function setCapacity(CapacityMegabyte $capacity): void
    {
        $this->capacity = $capacity;
    }


    public function getPrice(): Price
    {
        return $this->price;
    }


    public function setPrice(Price $price)
    {
        $this->price = $price;
    }


    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }


    public function setImageUrl(string $imageUrl): void
    {
        if (!str_starts_with($imageUrl, 'http')) {
            throw new \InvalidArgumentException("Invalid image Url in '$imageUrl'!");
        }
        $this->imageUrl = $imageUrl;
    }


    public function getAvailability(): Availability
    {
        return $this->availability;
    }


    public function setAvailability(Availability $availability): void
    {
        $this->availability = $availability;
    }


    public function setDelivery(?Delivery $delivery): void
    {
        $this->delivery = $delivery;
    }


    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle() . ' ' . $this->getCapacity(),
            'price' => $this->getPrice()->getAmount(),
            'imageUrl' => $this->getImageUrl(),
            'capacityMB' => $this->getCapacity()->getAmount(),
            'colour' => $this->getColour(),
            'availabilityText' => $this->getAvailability()->getText(),
            'isAvailable' => $this->getAvailability()->isAvailable(),
            'shippingText' => $this->getDelivery() ? $this->getDelivery()->getText() : null,
            'shippingDate' => $this->getDelivery() && ($date = $this->getDelivery()->getDate()) ? $date->toDateString() : null,
        ];
    }
}

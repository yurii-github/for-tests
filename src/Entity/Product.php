<?php

namespace App\Entity;

class Product implements \JsonSerializable
{
    protected const SUPPORTED_COLORS = [
        'green', 'black', 'blue', 'sky blue', 'grey', 'orange', 'white', 'yellow', 'slate grey'
    ];
    
    protected string $title;
    protected string $color;
    protected int $capacity;
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
    
    public function setColor(string $color)
    {
        if (!in_array($color, static::SUPPORTED_COLORS)) {
            throw new \InvalidArgumentException("Color '$color' is not supported!");
        }
        $this->color = $color;
    }
    
    public function getColor(): string
    {
        return $this->color;
    }
    
    public function getCapacity(): int
    {
        return $this->capacity;
    }
    
    public function setCapacity(int $capacity): void
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
            'title' => $this->getTitle(),
            'price' => $this->getPrice()->getAmount(),
            'imageUrl' => $this->getImageUrl(),
            'capacityMB' => $this->getCapacity(),
            'colour' => $this->getColor(),
            'availabilityText' => $this->getAvailability()->getText(),
            'isAvailable' => $this->getAvailability()->isAvailable(),
            'shippingText' => $this->getDelivery() ? $this->getDelivery()->getText() : null,
            'shippingDate' => $this->getDelivery() && ($date = $this->getDelivery()->getDate()) ? $date->toDateString() : null,
        ];
    }
}

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

    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    
    public function setColor(string $color)
    {
        $color = strtolower($color);
        if (!in_array($color, static::SUPPORTED_COLORS)) {
            throw new \InvalidArgumentException("Color '$color' is not supported!");
        }
        $this->color = $color;
    }
    
    public function getColor(): string
    {
        return $this->color;
    }
    
    public function getCapacity()
    {
        return $this->capacity;
    }
    
    public function setCapacity(string $capacity)
    {
        $knownTypes = ['MB', 'GB'];
        
        if (preg_match('/(\d+)\s?('.implode('|',$knownTypes).')/', $capacity, $m) !== 1) {
            throw new \InvalidArgumentException("Unknown capacity format '$capacity'!", 1);
        }
        
        $capacity = $m[1];
        $type = $m[2];
        
        if (intval($capacity) != $capacity) {
            throw new \InvalidArgumentException("Unknown capacity size!", 2);
        }
        
        if (!in_array($type, $knownTypes)) {
            throw new \InvalidArgumentException("Unknown capacity type!", 3);
        }
        
        if ($type == 'GB') {
            $capacity *= 1000;
        }

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
//            'availabilityText',
//            'isAvailable',
//            'shippingText',
//            'shippingDate'
        
            //{
//"availabilityText": "In Stock",
//"isAvailable": true,
//"shippingText": "Delivered from 25th March",
//"shippingDate": "2021-03-25"
//}
        ];
    }
}

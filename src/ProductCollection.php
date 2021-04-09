<?php

namespace App;

use App\Entity\Product;
use Illuminate\Support\Collection;

class ProductCollection extends Collection
{
    /**
     * @return $this
     */
    public function dedupedProducts()
    {
        return $this->keyBy(function (Product $product) {
            // NOTE: not sure if I got filter logic correct, but here it is applied the way I understood
            return strtolower($product->getTitle() . '-' . $product->getCapacity()->getAmount() . '-' . $product->getColour());
        })->values();
    }
}
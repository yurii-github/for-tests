<?php
namespace App;

use Illuminate\Support\Collection;

class ProductCollection extends Collection
{
    public function dedupedProducts()
    {
        return $this->all();
        // TODO: $this->items
    }
}
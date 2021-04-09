<?php

namespace App\Crawler;

use App\Entity\Product;

interface CrawlerInterface
{
    /**
     * @return Product[]
     */
    public function getAllProducts(): array;
}
<?php

namespace App\Crawler;

use App\ProductCollection;

interface CrawlerInterface
{
    public function getAllProducts(): ProductCollection;
}
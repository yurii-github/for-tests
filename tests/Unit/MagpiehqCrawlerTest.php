<?php
namespace tests\Unit;

use PHPUnit\Framework\TestCase;
use tests\MagpiehqTestCrawler;

class MagpiehqCrawlerTest extends TestCase
{
    public function testGetAllProducts()
    {
        $crawler = new MagpiehqTestCrawler();
        
        $products = $crawler->getAllProducts();
        $this->assertCount(15, $products);
        
    }
}
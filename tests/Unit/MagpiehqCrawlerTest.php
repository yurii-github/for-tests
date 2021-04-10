<?php

namespace tests\Unit;

use App\Crawler\CrawlerInterface;
use App\Crawler\MagpiehqCrawler;
use App\ProductCollection;
use PHPUnit\Framework\TestCase;
use App\Crawler\MagpiehqTestDataCrawler;

class MagpiehqCrawlerTest extends TestCase
{
    /**
     * This is very simplified test just to show that it works
     * 
     * @dataProvider providesCrawlers
     * @param CrawlerInterface $crawler
     */
    public function testGetAllProducts(CrawlerInterface $crawler)
    {
        $products = $crawler->getAllProducts();
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertCount(21, $products);
        $this->assertSame(file_get_contents(dirname(__DIR__) . '/data/magpiehq/products_all.json'),
            json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        $products = $products->dedupedProducts();
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertCount(20, $products);
        $this->assertSame(file_get_contents(dirname(__DIR__) . '/data/magpiehq/products_unique.json'),
            json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }


    public function providesCrawlers()
    {
        return [
            // uses static test data that we have fetched from real website, delivery like 'tomorrow' will fail
            // but for test task I guess it is sufficient
            [new MagpiehqTestDataCrawler()],
            // makes requests to real website changes, which changes shipping text randomly. so it will fail
            [new MagpiehqCrawler()],
        ];
    }
}
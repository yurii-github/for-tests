<?php

namespace tests\Unit;

use App\Crawler\CrawlerInterface;
use App\Crawler\MagpiehqCrawler;
use App\Entity\Product;
use App\ProductCollection;
use PHPUnit\Framework\TestCase;
use App\Crawler\MagpiehqTestDataCrawler;

class MagpiehqCrawlerTest extends TestCase
{
    /**
     * @dataProvider providesCrawlers
     * @param CrawlerInterface $crawler
     */
    public function testGetAllProducts(CrawlerInterface $crawler)
    {
        $products = $crawler->getAllProducts();
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertCount(21, $products);
        $this->assertSame(file_get_contents(dirname(__DIR__) . '/data/magpiehq/all_products.json'),
            json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        $products = $products->dedupedProducts();
        $this->assertInstanceOf(ProductCollection::class, $products);
        $this->assertCount(20, $products);
        $this->assertSame(file_get_contents(dirname(__DIR__) . '/data/magpiehq/unique_products.json'),
            json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }


    public function providesCrawlers()
    {
        return [
            // uses static test data that we have fetched from real website, delivery like 'tomorrow' will fail
            // but for test task I guess it is sufficient
            [new MagpiehqTestDataCrawler()],
            // makes requests to real website changes, so it will probably fail someday
            //[new MagpiehqCrawler()] 
        ];
    }
}
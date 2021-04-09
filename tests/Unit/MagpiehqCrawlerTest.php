<?php

namespace tests\Unit;

use App\Crawler\CrawlerInterface;
use App\Crawler\MagpiehqCrawler;
use App\ProductCollection;
use PHPUnit\Framework\TestCase;
use tests\MagpiehqTestCrawler;

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

        if ($crawler instanceof MagpiehqTestCrawler) {
            $this->assertSame(file_get_contents(dirname(__DIR__) . '/data/magpiehq/all_products.json'),
                json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );
        }
    }


    public function providesCrawlers()
    {
        return [
            [new MagpiehqTestCrawler()],
           // [new MagpiehqCrawler()]
        ];
    }
}
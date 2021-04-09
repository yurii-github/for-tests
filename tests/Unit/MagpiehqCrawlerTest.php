<?php
namespace tests\Unit;

use App\Crawler\MagpiehqCrawler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class MagpiehqCrawlerTest extends TestCase
{
    public function testGetAllProducts()
    {
        $crawler = new class() extends MagpiehqCrawler {
            protected function fetchPage(int $page): Crawler
            {
                $pageUrl = $this->buildPagedUrl($page);
                $testDataFile = dirname(__DIR__)."/data/page{$page}.html";
                $html = file_exists($testDataFile) ? file_get_contents(dirname(__DIR__)."/data/page{$page}.html") : '';
                return new Crawler($html, $pageUrl);
            }
        };
        
        $products = $crawler->getAllProducts();
        $this->assertCount(15, $products);
        
    }
}
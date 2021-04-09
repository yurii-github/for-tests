<?php
namespace App\Crawler;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class MagpiehqCrawler implements CrawlerInterface
{
    const BASEURL = 'https://www.magpiehq.com/developer-challenge/smartphones';

    /**
     * @inheritDoc
     */
    public function getAllProducts(): array
    {
        $products = [];
        for ($i = 1; $i <=4; $i++) {
            $page = $i;
            $products = array_merge($products, $this->parseProductsFromPage($this->fetchPage($page)));
            //file_put_contents("tests/data/page{$page}.html", $domCrawler->html());
        }
        
        return $products;
    }

    protected function parseProductsFromPage(Crawler $page): array
    {
        return []; // TODO:
    }

    protected function fetchPage(int $page): Crawler
    {
        $pageUrl = $this->buildPageUrl($page);
        $html = $this->fetchDocument($pageUrl);
        return new Crawler($html, $pageUrl);
    }
    
    protected function fetchDocument(string $url):string
    {
        $client = new Client();
        return (string)$client->get($url)->getBody();
    }

    private function buildPageUrl(int $page): string
    {
        return self::BASEURL."?page={$page}";
    }

}
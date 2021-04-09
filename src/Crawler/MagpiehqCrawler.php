<?php
namespace App\Crawler;

use App\Entity\Product;
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
        $page = 0;
        $products = [];
        
        do {
            $parsedProducts = $this->parseProductsFromPage($this->fetchPage(++$page));
            if (empty($parsedProducts)) {
                break;
            }
            $products = array_merge($products, $parsedProducts);
        } while($page < PHP_INT_MAX);
        
        return $products;
    }

    protected function parseProductsFromPage(Crawler $page): array
    {
        $products = [];
        
        $crawler = new Crawler();
        foreach ($page->filter('#products > div.flex.flex-wrap.-mx-4 > div') as $productNode) {
            $crawler->clear();
            $crawler->addNode($productNode);
            $product = new Product();
            $name = $crawler->filter('div > h3 > span.product-name')->text();
            $capacity = $crawler->filter('div > h3 > span.product-capacity')->text();
            $product->setTitle($name . ' ' . $capacity);
            $product->setCapacity($capacity);
            $colors = [];
            foreach ($crawler->filter('div > div:nth-child(3) > div > div > span') as $colorNode) {
                /** @var \DOMElement $colorNode */
                $colors[] = $colorNode->getAttribute('data-colour');
            }
            foreach ($colors as $color) {
                $product->setColor($color);
                $products[] = clone $product;
            }
            $products[] = $product;
        }
        
        return $products;
    }

    protected function fetchPage(int $page): Crawler
    {
        $pageUrl = $this->buildPagedUrl($page);
        $html = $this->fetchDocument($pageUrl);
        return new Crawler($html, $pageUrl);
    }
    
    protected function fetchDocument(string $url):string
    {
        $client = new Client();
        return (string)$client->get($url)->getBody();
    }

    protected function buildPagedUrl(int $page): string
    {
        return self::BASEURL."?page={$page}";
    }

}
<?php
namespace App\Crawler;

use App\DeliveryResolver;
use App\Entity\Availability;
use App\Entity\Delivery;
use App\Entity\Price;
use App\Entity\Product;
use App\ProductCollection;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class MagpiehqCrawler implements CrawlerInterface
{
    const BASEURL = 'https://www.magpiehq.com/developer-challenge/smartphones';

    /**
     * @inheritDoc
     */
    public function getAllProducts(): ProductCollection
    {
        $page = 0;
        $products = ProductCollection::empty();
        assert($products instanceof ProductCollection);
        
        do {
            $parsedProducts = $this->parseProductsFromPage($this->fetchPage(++$page));
            if ($parsedProducts->isEmpty()) {
                break;
            }
            $products = $products->merge($parsedProducts);
        } while($page < PHP_INT_MAX);
        
        return $products;
    }

    protected function parseProductsFromPage(Crawler $page): ProductCollection
    {
        $products = [];
        $page->filter('#products > div.flex.flex-wrap.-mx-4 > div')->each(function (Crawler $crawler) use (&$products) {
            $product = new Product();
            $name = $crawler->filter('div > h3 > span.product-name')->text();
            $capacity = $crawler->filter('div > h3 > span.product-capacity')->text();
            $product->setTitle($name . ' ' . $capacity);
            $product->setCapacity($capacity);
            $product->setPrice(new Price($crawler->filter('div > div.my-8.block.text-center.text-lg')->text()));
            $product->setImageUrl($crawler->filter('div > img')->image()->getUri());
            $product->setAvailability(new Availability($crawler->filter('div > div:nth-child(5)')->text()));
            $deliveryNode = $crawler->filter('div > div:nth-child(6)');
            $product->setDelivery($deliveryNode->count() ? self::resolveDelivery($deliveryNode->text()) : null);
            $colors = [];
            foreach ($crawler->filter('div > div:nth-child(3) > div > div > span') as $colorNode) {
                /** @var \DOMElement $colorNode */
                $colors[] = $colorNode->getAttribute('data-colour');
            }
            if ($colors) {
                foreach ($colors as $color) {
                    $product->setColor($color);
                    $products[] = clone $product;
                }
            } else {
                $products[] = $product;
            }
        });
        
        return ProductCollection::make($products);
    }


    protected static function resolveDelivery(string $delivery): ?Delivery
    {
        $text = null;
        $date = null;

        if (preg_match('/^Unavailable for delivery$/', $delivery, $match)) { // Unavailable for delivery
            $text = $match[0];
        } elseif (preg_match('/^Delivery by (.*)$/', $delivery, $match)) { // Delivery by 2021-04-10
            $text = $match[0];
            $date = Carbon::parse($match[1]);
        } elseif (preg_match('/^Available on (.*)$/', $delivery, $match)) { // Available on 16 Apr 2021
            $text = $match[0];
            $date = Carbon::parse($match[1]);
        } elseif (preg_match('/^Free Shipping$/', $delivery, $match)) { // Free Shipping
            $text = $match[0];
        } elseif (preg_match('/^Delivery from (.*)$/', $delivery, $match)) {  // Delivery from Sunday 9th May 2021
            $text = $match[0];
            $date = Carbon::parse($match[1]);
        } elseif (preg_match('/^Delivers (.*)$/', $delivery, $match)) { // Delivers 2021-04-09
            $text = $match[0];
            $date = Carbon::parse($match[1]);
        } elseif (preg_match('/^Free Delivery (.*)$/', $delivery, $match)) { // Free Delivery 10 Apr 2021 | Free Delivery tomorrow
            $text = $match[0];
            $date = Carbon::parse($match[1]);
        } elseif (preg_match('/^Free Delivery$/', $delivery, $match)) { // Free Delivery
            $text = $match[0];
        } elseif (preg_match('/^Order within \d+ hours and have it (.*)$/', $delivery, $match)) { // Order within 6 hours and have it 11 Apr 2021 
            $text = $match[0];
            $date = Carbon::parse($match[1]);
        } else {
            throw new \InvalidArgumentException("Unknown delivery text in '$delivery'!");
        }

        if ($text === null) {
            return null;
        }

        return new Delivery($text, $date);
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
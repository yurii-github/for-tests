<?php

namespace App\Crawler;

use App\Entity\Availability;
use App\Entity\CapacityMegabyte;
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
    const MAX_PAGES = 1000;

    /**
     * @inheritDoc
     */
    public function getAllProducts(): ProductCollection
    {
        $page = 1;
        $products = ProductCollection::empty();

        do {
            if ($page > self::MAX_PAGES) {
                throw new \LengthException("Too many pages. Last page was '$page'!");
            }
            $parsedProducts = $this->parseProductsFromPage($this->fetchPage($page));
            if ($parsedProducts->isEmpty()) {
                break;
            }
            $products = $products->merge($parsedProducts);
            $page++;
        } while (true);

        return $products;
    }


    protected function parseProductsFromPage(Crawler $page): ProductCollection
    {
        $products = [];
        $page->filter('#products > div.flex.flex-wrap.-mx-4 > div')->each(function (Crawler $crawler) use (&$products) {
            try {
                $productName = $crawler->filter('div > h3 > span.product-name')->text();
                $productCapacity = self::resolveCapacity($crawler->filter('div > h3 > span.product-capacity')->text());
                $productPrice = self::resolvePrice($crawler->filter('div > div.my-8.block.text-center.text-lg')->text());
                $productImageUrl = $crawler->filter('div > img')->image()->getUri();
                $productAvailability = self::resolveAvailability($crawler->filter('div > div:nth-child(5)')->text());
                $deliveryNode = $crawler->filter('div > div:nth-child(6)');
                $productDelivery = $deliveryNode->count() ? self::resolveDelivery($deliveryNode->text()) : null;
                $colours = [];
                foreach ($crawler->filter('div > div:nth-child(3) > div > div > span') as $colorNode) {
                    /** @var \DOMElement $colorNode */
                    $colours[] = strtolower($colorNode->getAttribute('data-colour'));
                }

                if (!$colours) {
                    throw new \Exception("We failed to fetch any colour for product!");
                }

                foreach ($colours as $colour) {
                    $product = new Product();
                    $product->setColour($colour);
                    $product->setTitle($productName);
                    $product->setCapacity(clone $productCapacity);
                    $product->setPrice(clone $productPrice);
                    $product->setImageUrl($productImageUrl);
                    $product->setAvailability(clone $productAvailability);
                    $product->setDelivery($productDelivery ? clone $productDelivery : $productDelivery);
                    $products[] = $product;
                }
            } catch (\Throwable $t) {
                throw new \Exception("ERROR! WEBPAGE: {$crawler->getUri()} | PRODUCT: $productName", 0, $t);
            }
        });

        return ProductCollection::make($products);
    }


    protected static function resolveAvailability(string $availability): Availability
    {
        $knownAvailabilities = [
            'out of stock' => false,
            'in stock at b90 4sb' => true,
            'in stock online' => true,
            'in stock' => true,
        ];

        $availability = strtolower($availability);
        $pattern = '/^availability:\s?(' . implode('|', array_keys($knownAvailabilities)) . ')$/';

        if (preg_match($pattern, $availability, $m) !== 1) {
            throw new \InvalidArgumentException("Unknown availability format in '$availability'!", 1);
        }

        $text = $m[1];

        if (!array_key_exists($text, $knownAvailabilities)) {
            throw new \InvalidArgumentException("Unknown availability status in '$availability'!", 2);
        }

        $isAvailable = $knownAvailabilities[$text];

        if ($text === 'in stock at b90 4sb') { // edge case, is this postcode or something?
            $text = 'in stock at B90 4SB';
        }

        return new Availability($text, $isAvailable);
    }


    protected static function resolveCapacity(string $capacity): CapacityMegabyte
    {
        $knownTypes = ['MB', 'GB'];

        if (preg_match('/^(\d+)\s?(' . implode('|', $knownTypes) . ')$/', $capacity, $m) !== 1) {
            throw new \InvalidArgumentException("Unknown capacity format in '$capacity'!", 1);
        }

        $capacity = $m[1];
        $type = $m[2];

        if (intval($capacity) != $capacity) {
            throw new \InvalidArgumentException("Invalid capacity size in '$capacity'!", 2);
        }

        if (!in_array($type, $knownTypes)) {
            throw new \InvalidArgumentException("Unknown capacity type in '$capacity'!", 3);
        }

        if ($type == 'GB') {
            $capacity *= 1000;
        }

        return new CapacityMegabyte((int)$capacity);
    }


    protected static function resolvePrice(string $price): Price
    {
        $knownCurrencies = ['£'];

        if (!preg_match('/^(' . implode('|', $knownCurrencies) . ')(.*)$/', $price, $m)) {
            throw new \InvalidArgumentException("Unknown price format in '$price'!", 1);
        }

        $currency = $m[1];
        $amount = $m[2];

        if (floatval($amount) != $amount) {
            throw new \InvalidArgumentException("Unknown price amount in '$price'!", 2);
        }

        if (!in_array($currency, $knownCurrencies)) {
            throw new \InvalidArgumentException("Unknown price currency in '$price'!", 2);
        }

        return new Price($amount, $currency);
    }


    protected static function resolveDelivery(string $delivery): ?Delivery
    {
        if (preg_match('/^Unavailable for delivery$/', $delivery, $match)) { // Unavailable for delivery
            return new Delivery($match[0]);
        }
        if (preg_match('/^Delivery by (.*)$/', $delivery, $match)) { // Delivery by 2021-04-10
            return new Delivery($match[0], Carbon::parse($match[1]));
        }
        if (preg_match('/^Available on (.*)$/', $delivery, $match)) { // Available on 16 Apr 2021
            return new Delivery($match[0], Carbon::parse($match[1]));
        }
        if (preg_match('/^Free Shipping$/', $delivery, $match)) { // Free Shipping
            return new Delivery($match[0]);
        }
        if (preg_match('/^Delivery from (.*)$/', $delivery, $match)) {  // Delivery from Sunday 9th May 2021
            return new Delivery($match[0], Carbon::parse($match[1]));
        }
        if (preg_match('/^Delivers (.*)$/', $delivery, $match)) { // Delivers 2021-04-09
            return new Delivery($match[0], Carbon::parse($match[1]));
        }
        if (preg_match('/^Free Delivery (.*)$/', $delivery, $match)) { // Free Delivery 10 Apr 2021 | Free Delivery tomorrow
            return new Delivery($match[0], Carbon::parse($match[1]));
        }
        if (preg_match('/^Free Delivery$/', $delivery, $match)) { // Free Delivery
            return new Delivery($match[0]);
        }
        if (preg_match('/^Order within \d+ hours and have it (.*)$/', $delivery, $match)) { // Order within 6 hours and have it 11 Apr 2021 
            return new Delivery($match[0], Carbon::parse($match[1]));
        }

        throw new \InvalidArgumentException("Unknown delivery text in '$delivery'!");
    }


    protected function fetchPage(int $page): Crawler
    {
        $pageUrl = $this->buildPagedUrl($page);
        $html = (string)(new Client())->get($pageUrl)->getBody();

        return new Crawler($html, $pageUrl);
    }


    protected function buildPagedUrl(int $page): string
    {
        return self::BASEURL . "?page={$page}";
    }

}
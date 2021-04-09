<?php

namespace tests;

use App\Crawler\MagpiehqCrawler;
use Symfony\Component\DomCrawler\Crawler;

class MagpiehqTestCrawler extends MagpiehqCrawler
{
    protected function fetchPage(int $page): Crawler
    {
        $pageUrl = $this->buildPagedUrl($page);
        $testDataFile = __DIR__."/data/magpiehq/page{$page}.html";
        $html = file_exists($testDataFile) ? file_get_contents($testDataFile) : '';
        return new Crawler($html, $pageUrl);
    }
}
<?php

namespace App\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class MagpiehqTestDataCrawler extends MagpiehqCrawler
{
    protected function fetchPage(int $page): Crawler
    {
        $pageUrl = $this->buildPagedUrl($page);
        $testDataFile = dirname(__DIR__, 2) . "/tests/data/magpiehq/page{$page}.html";
        $html = file_exists($testDataFile) ? file_get_contents($testDataFile) : '';

        return new Crawler($html, $pageUrl);
    }
}
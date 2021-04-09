<?php

namespace App\Commands;

use App\Crawler\MagpiehqCrawler;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

class ScrapeCommand extends Command
{
    private array $products = [];

    protected function configure(): void
    {
        $this
            ->setName('scrape')
            ->setDescription('Scrapes test data from magpiehq.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $crawler = new MagpiehqCrawler();
        $products = $crawler->getAllProducts();
        
        // TODO: logic
        
      //  file_put_contents('output.json', json_encode($this->products));

        return Command::SUCCESS;
    }
}


<?php

namespace App\Commands;

use App\Crawler\MagpiehqCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use tests\MagpiehqTestCrawler;

class ScrapeCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('scrape')
            ->setDescription('Scrapes test data from magpiehq.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $crawler = new MagpiehqCrawler();
        $crawler = new MagpiehqTestCrawler();
        $products = $crawler->getAllProducts();
        
        // TODO: products filter logic
        
        file_put_contents('output.json', json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}


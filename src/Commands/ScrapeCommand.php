<?php

namespace App\Commands;

use App\ScrapeHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $document = ScrapeHelper::fetchDocument('https://www.magpiehq.com/developer-challenge/smartphones');
        file_put_contents('output.json', json_encode($this->products));
        
        return Command::SUCCESS;
    }
}

